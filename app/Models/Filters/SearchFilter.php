<?php

namespace App\Models\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SearchFilter
{
    static function apply(Builder $builder, array $filters)
    {
        foreach ($filters as $relation => $conditions) {
            if (is_array($conditions)) {
                $builder->whereHas($relation, function ($q) use ($conditions) {
                    foreach ($conditions as $field => $value) {
                        $q->where($field, 'like', '%' . $value . '%');
                    }
                });
            } else {
                $builder->where($relation, 'like', '%' . $conditions . '%');
            }
        }

        return $builder;
    }
}
