<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'code',
        'name',
        'category',
        'price',
    ];

    protected $casts = [
        'price' => 'float'
    ];


    function order_products(): HasMany
    {
        return $this->hasMany(OrderProduct::class, 'product_code', 'code');
    }

    function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, OrderProduct::class, 'item_code', 'order_id')->withPivot('quantity', 'total_price');
    }
}
