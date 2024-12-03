<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProduct extends Model
{
    public $incrementing = false;
    protected $table = 'order_products';
    protected $keyType = 'string';
    protected $primaryKey = ['order_id', 'product_code'];
    protected $casts = ['total_price' => 'float'];
    protected $fillable = ['order_id', 'product_code', 'quantity', 'total_price'];

    function orders(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    function products(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_code', 'code');
    }
}
