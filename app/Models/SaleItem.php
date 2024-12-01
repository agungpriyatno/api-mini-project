<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    public $incrementing = false;
    protected $table = 'sale_items';
    protected $keyType = 'string';
    protected $primaryKey = ['sale_id', 'item_code'];
    protected $casts = ['total_price' => 'float'];
    protected $fillable = ['sale_id', 'item_code', 'quantity', 'total_price'];

    function sales(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    function items(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_code', 'code');
    }
}
