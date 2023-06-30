<?php

namespace App\Exports;

use App\Models\Table;
use App\Models\TableItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class TableExport implements FromCollection
{
    protected $id;

    function __construct($id) {
        $this->id = $id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $table = Table::where('id',1)->with('rows','columnsList')->first();
        $collection = new Collection();
        $titles = [];
        foreach ($table->columnsList as $column){
            $titles[] = $column->title;
        }
        $collection->push($titles);
        $rows = TableItem::where('table_id',$table->id)->get()->groupBy('row_id');
        $rows->map(function ($item) use($collection){
            $row = [];
            foreach ($item as $column){
                $row[] = $column->value;
            }
            $collection->push($row);
            return true;
        });
        return $collection;
    }
}
