<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    //
    use HasUuids;
    use HasFactory;

    protected $table = 'customers';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'domicile',
        'gender',
    ];

    function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'customer_id', 'id');
    }
}
