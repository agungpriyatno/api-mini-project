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

class Sale extends Model
{
    use HasUuids;
    public $incrementing = false;
    protected $table = 'sales';
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

    function sale_items(): HasMany
    {
        return $this->hasMany(SaleItem::class, 'sale_id', 'id');
    }


    function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, SaleItem::class, 'sale_id', 'item_code')->withPivot('quantity', 'total_price');
    }
}
