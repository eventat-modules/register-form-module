<?php

namespace App\Http\Filters;

use AhmedAliraqi\LaravelFilterable\BaseFilter;
use App\Models\__CRUD_STUDLY_SINGULAR__;
use Illuminate\Database\Eloquent\Builder;

class __CRUD_STUDLY_SINGULAR__Filter extends BaseFilter
{
    /**
     * Registered filters to operate upon.
     */
    protected array $filters = [];

    public function __construct(array $data = [], array $include = [])
    {
        parent::__construct($data, $include);

        $this->filters = __CRUD_STUDLY_SINGULAR__::filterFieldNames();
    }

    public function apply(Builder $builder): Builder
    {
        foreach ($this->filters as $filter) {
            $value = data_get($this->data, $filter);

            $builder->where($filter, 'like', "%$value%");
        }

        return $builder;
    }
}
