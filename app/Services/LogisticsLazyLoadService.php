<?php

namespace App\Services;

use App\Models\Logistics;
use App\Services\Contracts\LogisticsLazyLoadServiceContract;
use Carbon\Carbon;

class LogisticsLazyLoadService implements LogisticsLazyLoadServiceContract
{
    private $params;
    private $preparedQueries;
    private $totalRecord = 0;

    /**
     * @param $request
     * @return $this
     */
    public function prepareTableParams($request)
    {
        $order = $request->query('order', [0, 'desc']);
        $sortColumns = [
            0 => "id",
            1 => "date",
            2 => "name",
            3 => "phone",
            4 => "car_type",
            5 => "car_number",
        ];

        $sortValue = $sortColumns[$order[0]['column']];
        $sortDirection = $order[0]['dir'];

        $this->params = collect([
            "search" => $request->query('search', ['value' => '', 'regex' => false]),
            "draw" => $request->query('draw', 0),
            "start" => $request->query('start', 0),
            "length" => $request->query('length', 25),
            "order" => $request->query('order', [0, 'desc']),
            "startDate" => $request->post('start_date'),
            "endDate" => $request->post('end_date'),
            "sortValue" => $sortValue,
            "sortDirection" => $sortDirection,
            "fastDateFilter" => $request->post('fast_date_filter'),
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    public function prepareQueries()
    {
        $tableParams = $this->getParams();
        $queries = Logistics::query();

        if (!empty($tableParams->get('fastDateFilter'))) {
            if ($tableParams->get('fastDateFilter') == 'today') {
                $queries->where('date', '>=', Carbon::today());
                $queries->where('date', '<=', Carbon::tomorrow());
            } elseif ($tableParams->get('fastDateFilter') == 'tomorrow') {
                $queries->where('date', '>=', Carbon::tomorrow());
                $queries->where('date', '<=', Carbon::tomorrow()->addDay());
            }
        }

        if (!empty($tableParams->get("startDate")) && !empty($tableParams->get("endDate"))) {
            $queries->whereBetween('created_at', [
                Carbon::createFromTimestamp(strtotime($tableParams->get("startDate"))),
                Carbon::createFromTimestamp(strtotime($tableParams->get("endDate")) + 3600 * 24) //Делаем + 24 часа
            ]);
        }

        $filter = trim($tableParams->get("search")['value']);
        $queries->when($filter, function ($queries, $filter) {
            $queries->where(function ($queries) use ($filter) {
                $queries->orWhere('name', 'like', "%$filter%")
                        ->orWhere('phone', 'like', "%$filter%")
                        ->orWhere('car_type', 'like', "%$filter%")
                        ->orWhere('car_number', 'like', "%$filter%");
            });
        });

        $this->setTotalRecord($queries->count());

        $queries->orderBy($tableParams->get("sortValue"), $tableParams->get("sortDirection"))
                ->take($tableParams->get("length"))
                ->skip($tableParams->get("start"));

        $this->setPreparedQueries($queries->get());
        return $this;
    }

    /**
     * @return array
     */
    public function initLazyLoad(): array
    {
        $preparedQueries = $this->getPreparedQueries();
        $tableParams = $this->getParams();

        $json = array(
            'draw' => $tableParams->get("draw"),
            'recordsTotal' => $this->getTotalRecord(),
            'recordsFiltered' => $this->getTotalRecord(),
            'data' => [],
        );

        foreach ($preparedQueries as $query) {
            $json['data'][] = [
                $query->query_id,
                date('m-d H:i', strtotime($query->date)),
                $query->name,
                $query->phone,
                $query->car_type,
                $query->car_number,
                $query->cutter,
                $query->loader,
                $query->oxygen,
            ];
        }

        return $json;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return int
     */
    public function getTotalRecord(): int
    {
        return $this->totalRecord;
    }

    /**
     * @param int $totalRecord
     */
    public function setTotalRecord(int $totalRecord)
    {
        $this->totalRecord = $totalRecord;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPreparedQueries()
    {
        return $this->preparedQueries;
    }

    /**
     * @param mixed $preparedQueries
     */
    public function setPreparedQueries($preparedQueries)
    {
        $this->preparedQueries = $preparedQueries;
        return $this;
    }
}
