<?php

namespace App\Services;

use App\Models\Cost;
use App\Models\Item;
use App\Models\ResponsibleType;
use App\Services\Contracts\CostLazyLoadServiceContract;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CostLazyLoadService implements CostLazyLoadServiceContract
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
            "costParams" => $request->post('costParams') ?? [],
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    public function prepareQueries()
    {
        //Получаем все данные DataTable
        $tableParams = $this->getParams();

        //Значение Поиска DataTable
        $filter = trim($tableParams->get("search")['value']);

        //Формируем основной запрос
        $costs = Cost::where('user_id', auth()->id())
                     ->when($tableParams['costParams']['item'], function ($query) use ($tableParams) {
                         return $query->where('item_id', $tableParams['costParams']['item']);
                     })
                     ->when($tableParams['costParams']['cashless'], function ($query) use ($tableParams) {
                         return $query->where('cashless', $tableParams['costParams']['cashless']);
                     })
                     ->with(['item', 'responsibles' => function ($responsible) {
                         $responsible->with('type');
                     }])
                     ->orderBy($tableParams->get("sortValue"), $tableParams->get("sortDirection"));

        //Фильтрация по поиску
        $costs->when($filter, function ($costs, $filter) {
            $costs->where(function ($costs) use ($filter) {
                $costs->orWhere('value', 'like', "%$filter%")
                      ->orWhere('date', 'like', "%$filter%")
                      ->orWhere('comment', 'like', "%$filter%")
                      ->orWhereHas('responsibles', function ($query) use ($filter) {
                          return $query->where('name', 'like', "%$filter%");
                      });
            });
        });

        //Берем общее коилчество записей для формирования пагинации
        $costCount = $costs->count();
        $this->setTotalRecord($costCount);

        //Иниицируем запрос относительно длины таблицы и пагинации
        $costs = $costs->take($tableParams->get("length"))
                       ->skip($tableParams->get("start"))
                       ->get();

        foreach ($costs as $cost) {
            $cost->responsibleValues = $cost->responsibles->groupBy('type_id');
            $cost->value = self::makeMoney($cost->value);
        }

        $additionalColumns = ResponsibleType::where('user_id', auth()->id())->orderBy('order')->get();

        if (!empty($tableParams['costParams']['item'])) {
            $service_item = Item::find($tableParams['costParams']['item']);
            if(!empty($service_item)){
                $defaults = $service_item->defaultResponsibles;
            }
        }

        $this->setPreparedQueries([
            'additionalColumns' => $additionalColumns,
            'costs' => $costs,
            'formRequiredFields' => [
                'required_count' => $service_item->required_count ?? null,
                'required_responsible' => $service_item->required_responsible ?? null,
            ],
            'defaults' => $defaults ?? [],
        ]);

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
            'formRequiredFields' => $preparedQueries['formRequiredFields'],
            'defaults' => $preparedQueries['defaults'],
        );

        foreach ($preparedQueries['costs'] as $key => $cost) {
            //Добавляем первичные поля таблицы
            $json['data'][$key] = [
                view('includes.costs.dataTableElements.date', [
                    'cost' => $cost,
                ])->render(),
                $cost->item->title,
                $cost->count,
                view('includes.costs.dataTableElements.costValue', [
                    'cost' => $cost,
                ])->render(),
            ];

            //Добавляем динамичные поля табицы
            foreach ($preparedQueries['additionalColumns'] as $additional) {
                $additionalValue = '';
                if (Arr::exists($cost->responsibleValues, $additional->id)) {
                    $additionalValue = $cost->responsibleValues[$additional->id]->first()->name;
                }
                array_push($json['data'][$key], $additionalValue);
            }
            //Добавляем комменты в таблицу
            array_push($json['data'][$key], view('includes.costs.dataTableElements.comment', [
                'cost' => $cost,
            ])->render());

            //Добавляем действия в таблицу
            array_push($json['data'][$key], view('includes.costs.dataTableElements.actions', [
                'cost' => $cost,
            ])->render());
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
                $result = ',' . substr($string, -3) . $result;
                $string = substr($string, 0, -3);
            } else {
                $result = $string . $result;
                $check = false;
            }
        }
        if (strlen($result)) {
            $result .= '₽';
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
