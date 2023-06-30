<?php

namespace App\Exports;

use App\Models\Cost;
use App\Models\ExportGroup;
use App\Models\Item;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class OutcomeExport implements FromCollection, WithColumnWidths ,WithStyles
{
    protected $request;
    protected $bold_rows;
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->bold_rows = [];
    }
    public function makeMoney($money){
        $string = strval($money);
        if(!$string){
            $string = '0';
        }
        $result = '';
        $check = true;
        while($check){
            if(strlen($string)>3){
                $result = ','.substr($string, -3).$result;
                $string = substr($string,0, -3);
            }else{
                $result = $string.$result;
                $check = false;
            }
        }
        if(strlen($result)){
            $result .= ' ₽';
        }
        return $result;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $request = $this->request;
        $main_arr = [];
        $main_sum = 0;
        foreach ($request->group as $group) {
            if(Arr::exists($group,'items')){
                $this->bold_rows[] = 1;
                if($group['group_name']){
                    $main_arr[] = [$group['group_name'],''];
                    $this->bold_rows[] = count($main_arr);
                }else{
                    $main_arr[] = ['',''];
                }
                $group_sum = 0;
                foreach ($group['items'] as $item) {
                    $item_to_export = Item::where('id',$item['id'])->with('subItems')->first();
                    if($item_to_export){
                        $costs = null;
                        if(count($item_to_export->subItems)){
                            $ids = $item_to_export->subItems->pluck('id');
                            $costs = Cost::whereIn('item_id',$ids)->get();
                            $sum = $costs->sum('value');
                            $main_arr[] = [$item_to_export->title,$this->makeMoney($sum)];
                            $group_sum += $sum;
                        }else{
                            $costs = Cost::where('item_id',$item_to_export->id)->get();
                            $sum = $costs->sum('value');
                            $main_arr[] = [$item_to_export->title,$this->makeMoney($costs->sum('value'))];
                            $group_sum += $sum;
                        }
                    }
                }
                $main_arr[] = ['Итог',$this->makeMoney($group_sum)];
                $this->bold_rows[] = count($main_arr);
                $main_arr[] = ['',''];
                $main_sum += $group_sum;

            }
        }
        $main_arr[] = ['',''];
        $main_arr[] = ['',''];
        $main_arr[] = ['',''];
        $main_arr[] = ['Общий Итог',$this->makeMoney($main_sum)];
        $this->bold_rows[] = count($main_arr);
        return collect($main_arr);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 25,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $style_rows = [];
        foreach ($this->bold_rows as $row_id){
            $style_rows[$row_id] = ['font' => ['bold' => true, 'size' => 12]];
        }
        $style_rows['B'] = ['font' => ['italic' => true]];
        return $style_rows;
    }
}
