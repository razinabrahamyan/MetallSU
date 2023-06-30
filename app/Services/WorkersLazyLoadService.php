<?php

namespace App\Services;

use App\Models\Base;
use App\Models\Logistics;
use App\Models\Worker;
use Carbon\Carbon;

class WorkersLazyLoadService
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
            0 => "created_at",
            1 => "category",
            2 => "name",
            3 => "salary",
            4 => "date",
            5 => "additional",
        ];

        $sortValue = $sortColumns[$order[0]['column']];
        $sortDirection = $order[0]['dir'];

        $this->params = collect([
            "search" => $request->query('search', ['value' => '', 'regex' => false]),
            "draw" => $request->query('draw', 0),
            "start" => $request->query('start', 0),
            "length" => $request->query('length', 25),
            "order" => $request->query('order', [0, 'desc']),
            "sortValue" => $sortValue,
            "sortDirection" => $sortDirection,
            "params" => $request->post('params') ?? [],
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    public function prepareQueries()
    {
        $tableParams = $this->getParams();
        $queries = Worker::query();
        $queries->where('user_id',auth()->id())->with('status');

        $filter = trim($tableParams->get("search")['value']);
        $queries->when($filter, function ($queries, $filter) {
            $queries->where(function ($queries) use ($filter) {
                $queries->orWhere('name', 'like', "%$filter%")
                    ->orWhere('additional', 'like', "%$filter%")
                    ->orWhere('salary', 'like', "%$filter%");
            });
        });
        $queries->when($tableParams['params']['category'], function ($queries) use ($tableParams) {
            $queries->where('category_id',$tableParams['params']['category']);
        },function ($queries) use ($tableParams){
            $queries->when($tableParams['params']['base'], function ($queries) use ($tableParams) {
                $queries->whereHas('category',function ($category) use ($tableParams){
                    $category->where('base_id',$tableParams['params']['base']);
                });
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
                $query->category->base->title,
                $query->category->title,
                $query->name,
                self::makeMoney($query->salary),
                $query->additional,
                view('includes.workers.dataTableElements.status', [
                    'status' => $query->status,
                ])->render(),
            ];
        }

        return $json;
    }
    public static function makeMoney($money): string
    {
        $string = strval($money);
        if (!$string) {
            $string = '0';
        }
        $result = '';
        $check = true;
        while ($check) {
            if (strlen($string) > 3) {
                $result = ', ' . substr($string, -3) . $result;
                $string = substr($string, 0, -3);
            } else {
                $result = $string . $result;
                $check = false;
            }
        }
        if (strlen($result)) {
            $result .= ' ₽';
        }
        return $result;
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
