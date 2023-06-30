<?php
namespace App\Http\Traits;

use App\Models\Table;
use App\Models\TableColumn;
use App\Models\TableItem;

trait StatisticTrait {

    public function getStatistic($category,$table) {
        $columns = TableColumn::where('type','price')->get()->pluck('id');
        $allTableItems = null;
        if($table === 'all'){
            $tables = Table::where('category_id',$category)->get()->pluck('id');
            $allTableItems = TableItem::whereIn('table_id',$tables)->whereIn('column_id',$columns)->with(['tableColumns','table'])->get()->groupBy(['table.title','tableColumns.title']);
        }else{
            $allTableItems = TableItem::where('table_id',$table)->whereIn('column_id',$columns)->with(['tableColumns','table'])->get()->groupBy(['table.title','tableColumns.title']);
        }

        $itog = [];
        foreach ($allTableItems as $tableName => $group){
            $small_itog = [];
            foreach ($group as $columnName => $itemGroup) {
                $itemGroup->sum = $itemGroup->sum('value');
                $small_itog[] = [
                    'sum' => $itemGroup->sum('value'),
                    'small_group' => $itemGroup,
                    'columnName' => $columnName
                ];
            }
            $itog[] = [
                'sum' => $group->sum('sum'),
                'group' => $small_itog,
                'tableName' => $tableName,
                'tableId' => $small_itog[0]['small_group'][0]->table->id
            ];
            $group->sum = $group->sum('sum');
        }
        return $itog;
    }
}
