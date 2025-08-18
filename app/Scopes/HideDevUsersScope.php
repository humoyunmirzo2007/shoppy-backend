<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\App;

class HideDevUsersScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if ($this->shouldHideDevUsers()) {
            $builder->where($model->getTable() . '.is_dev', false);
        }
    }

    protected function shouldHideDevUsers(): bool
    {
        if (App::runningInConsole()) {
            return false;
        }

        if (!app()->bound('request')) {
            return false;
        }

        if (!auth()->check()) {
            return false;
        }

        $user = auth()->user();

        if (!$user->is_dev) {
            return true;
        }

        return false;
    }


}
