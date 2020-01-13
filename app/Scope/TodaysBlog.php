<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Carbon;

class TodaysBlog implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        $builder->where('created_at', '>', Carbon::yesterday());
    }
}
