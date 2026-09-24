<?php

namespace App\Models;

use App\Support\Settings;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $fillable = [
        'medicine_id', 'batch_number', 'manufacturing_date', 'expiry_date',
        'quantity', 'minimum_stock_level', 'purchase_price', 'selling_price',
        'supplier_name',
    ];

    protected function casts(): array
    {
        return [
            'manufacturing_date' => 'date',
            'expiry_date' => 'date',
            'quantity' => 'integer',
            'minimum_stock_level' => 'integer',
            'purchase_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
        ];
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class)->latest();
    }

    /*
    |--------------------------------------------------------------------------
    | Reusable status logic (Rules 4-7 / requirement #45)
    |--------------------------------------------------------------------------
    */

    public function getStockStatus(): string
    {
        if ($this->quantity <= 0) {
            return 'OUT OF STOCK';
        }

        if ($this->quantity <= $this->minimum_stock_level) {
            return 'LOW STOCK';
        }

        return 'IN STOCK';
    }

    public function getExpiryStatus(): string
    {
        $days = $this->getDaysRemaining();

        if ($days < 0) {
            return 'EXPIRED';
        }

        if ($days <= Settings::get('near_expiry_days', 30)) {
            return 'NEAR EXPIRY';
        }

        return 'VALID';
    }

    /** Positive = days until expiry, negative = days since it expired. */
    public function getDaysRemaining(): int
    {
        return (int) Carbon::today()->diffInDays($this->expiry_date, false);
    }

    public function stockBadgeColor(): string
    {
        return match ($this->getStockStatus()) {
            'OUT OF STOCK' => 'red',
            'LOW STOCK' => 'orange',
            default => 'green',
        };
    }

    public function expiryBadgeColor(): string
    {
        return match ($this->getExpiryStatus()) {
            'EXPIRED' => 'red',
            'NEAR EXPIRY' => 'orange',
            default => 'green',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Query scopes — mirror the same rules at the database level so filters,
    | dashboard counts, and reports all stay in sync with model-level logic.
    |--------------------------------------------------------------------------
    */

    public function scopeOutOfStock(Builder $query): Builder
    {
        return $query->where('quantity', '<=', 0);
    }

    public function scopeLowStock(Builder $query): Builder
    {
        return $query->whereColumn('quantity', '<=', 'minimum_stock_level')->where('quantity', '>', 0);
    }

    public function scopeInStock(Builder $query): Builder
    {
        return $query->whereColumn('quantity', '>', 'minimum_stock_level');
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->whereDate('expiry_date', '<', Carbon::today());
    }

    public function scopeNearExpiry(Builder $query, ?int $days = null): Builder
    {
        $days ??= Settings::get('near_expiry_days', 30);

        return $query->whereDate('expiry_date', '>=', Carbon::today())
            ->whereDate('expiry_date', '<=', Carbon::today()->addDays($days));
    }

    public function scopeValid(Builder $query, ?int $days = null): Builder
    {
        $days ??= Settings::get('near_expiry_days', 30);

        return $query->whereDate('expiry_date', '>', Carbon::today()->addDays($days));
    }

    public function scopeExpiringBetween(Builder $query, $from, $to): Builder
    {
        return $query->whereDate('expiry_date', '>=', $from)->whereDate('expiry_date', '<=', $to);
    }
}
