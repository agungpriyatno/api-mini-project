<?php

namespace App\Models;

use App\Models\Filters\Filter;
use App\Models\Filters\SearchFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Order extends Model
{
    use HasUuids;
    public $incrementing = false;
    protected $table = 'orders';
    protected $primaryKey = 'id';

    protected $fillable = [
        'customer_id',
        'total_price',
    ];

    protected $casts = [
        'total_price' => 'float'
    ];


    function customers(): BelongsTo
    {
        return  $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    function order_products(): HasMany
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
    }


    function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, OrderProduct::class, 'order_id', 'product_code')->withPivot('quantity', 'total_price');
    }
}
