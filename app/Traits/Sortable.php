<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Sortable
{
    public function scopeSortable(Builder $query, $columns = ['id' => 'desc'])
    {
        $allowedColumns = $this->sortable ?? ['id'];

        foreach ($columns as $column => $direction) {
            if (in_array($column, $allowedColumns)) {
                $query->orderBy($column, in_array($direction, ['asc', 'desc']) ? $direction : 'desc');
            }
        }

        return $query;
    }
}
