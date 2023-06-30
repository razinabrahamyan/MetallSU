<?php

namespace App\Services\Contracts;

interface CostLazyLoadServiceContract
{
    public function prepareTableParams($request);

    public function prepareQueries();

    public function initLazyLoad(): array;
}
