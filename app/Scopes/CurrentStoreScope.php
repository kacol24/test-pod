<?php

namespace App\Scopes;

use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class CurrentStoreScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('store_id', session(Store::SESSION_KEY)->id);
    }
}
