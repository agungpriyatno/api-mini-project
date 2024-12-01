<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';
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




    function sale_items(): HasMany
    {
        return $this->hasMany(SaleItem::class, 'item_code', 'code');
    }

    function sales(): BelongsToMany
    {
        return $this->belongsToMany(Sale::class, SaleItem::class, 'item_code', 'sale_id')->withPivot('quantity', 'total_price');
    }
}
