<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{
    protected $fillable = [
        'batch_id', 'user_id', 'transaction_type', 'quantity',
        'previous_quantity', 'new_quantity', 'reason',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function typeBadgeColor(): string
    {
        return match ($this->transaction_type) {
            'IN' => 'green',
            'OUT' => 'red',
            default => 'blue',
        };
    }
}
