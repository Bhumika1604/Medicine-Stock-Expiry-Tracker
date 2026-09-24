<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medicine extends Model
{
    use HasFactory, SoftDeletes;

    public const DOSAGE_FORMS = [
        'Tablet', 'Capsule', 'Syrup', 'Injection', 'Cream',
        'Ointment', 'Drops', 'Powder', 'Other',
    ];

    protected $fillable = [
        'category_id', 'name', 'generic_name', 'manufacturer',
        'dosage_form', 'strength', 'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    /** Total quantity across all batches. */
    public function totalStock(): int
    {
        return (int) $this->batches->sum('quantity');
    }

    /** Overall medicine-level stock status, derived from total quantity vs. the
     *  sum of its batches' minimum stock levels. */
    public function getStockStatus(): string
    {
        $qty = $this->totalStock();
        $minLevel = (int) $this->batches->sum('minimum_stock_level');

        if ($qty === 0) {
            return 'OUT OF STOCK';
        }

        if ($minLevel > 0 && $qty <= $minLevel) {
            return 'LOW STOCK';
        }

        return 'IN STOCK';
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('generic_name', 'like', "%{$term}%")
                ->orWhere('manufacturer', 'like', "%{$term}%")
                ->orWhereHas('batches', function ($b) use ($term) {
                    $b->where('batch_number', 'like', "%{$term}%");
                });
        });
    }

    public function scopeCategory(Builder $query, $categoryId): Builder
    {
        return $categoryId ? $query->where('category_id', $categoryId) : $query;
    }

    public function scopeManufacturer(Builder $query, $manufacturer): Builder
    {
        return $manufacturer ? $query->where('manufacturer', $manufacturer) : $query;
    }

    /** Medicines whose current stock status matches (computed in PHP after load,
     *  since status is derived — used for small/medium result sets after eager load). */
    public function scopeWithStockTotals(Builder $query): Builder
    {
        return $query->withSum('batches as total_quantity', 'quantity')
            ->withSum('batches as total_min_level', 'minimum_stock_level')
            ->withCount('batches as batches_count');
    }
}
