<?php

namespace App\Services\Contracts;

interface LogisticsLazyLoadServiceContract
{
    public function prepareTableParams($request);

    public function prepareQueries();

    public function initLazyLoad(): array;
}
