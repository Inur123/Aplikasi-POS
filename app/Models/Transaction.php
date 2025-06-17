<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
        'invoice',
        'total_price',
        'payment',
        'change',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }
    public function transactionItems()
{
    return $this->hasMany(TransactionItem::class);
}
}
