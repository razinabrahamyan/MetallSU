<?php

namespace App\Exports;

use App\Models\Cost;
use App\Models\ExportGroup;
use App\Models\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FullExport implements FromCollection, WithColumnWidths, WithStyles, WithEvents
{
    protected $request;
    protected $bold_rows;
    protected $italic_rows;
    protected $should_not_be_italic;
    protected $should_be_centered;
    protected $should_merge;
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->bold_rows = [];
        $this->italic_rows = [];
        $this->should_not_be_italic = [];
        $this->should_be_centered = [];
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
        $title = 'Итоговый отчет ';
        if ($request->from){
            $title.= ' От  -  '.$request->from.'  ';
        }
        if ($request->to){
            $title .= '  До  -  '.$request->to;
        }
        $main_arr[] = [$title];
        $this->bold_rows[] = count($main_arr);
        $this->italic_rows[] = count($main_arr);
        $main_arr[] = ['',''];

        if($request->show_items){
            $main_arr[] = ['Группа','Сумма','Позиции Группы'];
            $this->bold_rows[] = count($main_arr);
            $this->should_merge = 'A1:C1';

        }else{
            $main_arr[] = ['Группа','Сумма'];
            $this->bold_rows[] = count($main_arr);
            $this->should_merge = 'A1:B1';
        }
        $this->should_not_be_italic[] = count($main_arr);
        $this->should_be_centered[] = count($main_arr);

        $export_groups = ExportGroup::where('user_id',auth()->id())->with(['items' => function($item){
            $item->with('parentItem');
        }])->get();
        foreach ($export_groups as $index => $group) {
            $ids = $group->items->pluck('id');

            $costs = Cost::whereIn('item_id', $ids)->where('cashless',$request->cashless)
                         ->when($request->from, function ($query) use ($request) {
                             $query->whereDate('date', '>=', Carbon::parse($request->from)->format('Y-m-d'));
                         })->when($request->to, function ($query) use ($request) {
                    $query->whereDate('date', '<=', Carbon::parse($request->to)->format('Y-m-d'));
                })->get();
            $sum = $costs->sum('value');
            if($sum || !$request->hide_nulls){
                if($request->show_items){
                    $items = $group->items->pluck('title');
                    $item_string = '';
                    if($request->show_item_costs){
                        foreach ($group->items as $loop => $item) {
                            $arr = $costs->where('item_id',$item->id);
                            $item_sum = $arr->sum('value');
                            if($item_sum){
                                $item_string.=' '.($loop + 1).'. '.Str::upper($item->title);
                                $item_string.= ' ('.$this->makeMoney($item_sum);
                                $item_count = $arr->sum('count');
                                if($item_count){
                                    $item_string.= ' | > '.$arr->sum('count').')';
                                }else{
                                    $item_string.= ')';
                                }

                                if($loop !== count($group->items)-1){
                                    $item_string.= ', ';
                                }
                            }
                        }
                    }else{
                        foreach ($items as $loop => $item) {
                            $item_string.=' '.($loop + 1).'. '.Str::upper($item);
                            if($loop !== count($items)-1){
                                $item_string.= ',';
                            }
                        }
                    }

                    $main_arr[] = [Str::upper($group->title),$this->makeMoney($sum), $item_string];
                }else{
                    $main_arr[] = [$group->title,$this->makeMoney($sum)];
                }
            }



            $main_sum += $sum;
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
        if($this->request->show_items){
            return [
                'A' => 40,
                'B' => 40,
                'C' => 100
            ];
        }
        return [
            'A' => 40,
            'B' => 40,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $style_rows = [];
        foreach ($this->bold_rows as $row_id){
            $style_rows[$row_id] = ['font' => ['bold' => true, 'size' => 12]];
        }
        foreach ($this->italic_rows as $row_id){
            $style_rows[$row_id]['font']['italic'] = true;

        }
        $style_rows['B'] = ['font' => ['italic' => true]];
        foreach ($this->should_not_be_italic as $row_id){
            $style_rows['B'.$row_id]['font']['italic'] = false;
        }
        return $style_rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->getStyle('C')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('A')->getAlignment()->setVertical('top');
                $event->sheet->getStyle('B')->getAlignment()->setVertical('top');
                $event->sheet->getStyle('C')->getAlignment()->setVertical('top');
                $event->sheet->getStyle('1')->getAlignment()->setHorizontal('center');
                $event->sheet->mergeCells($this->should_merge);

                foreach ($this->should_not_be_italic as $row_id){
                    $event->sheet->getStyle($row_id)->getAlignment()->setHorizontal('center');
                }
                $event->sheet->getStyle('A1')->applyFromArray(array(
                    'font' => array(
                        'color' => array('rgb' => '000000'),

                    )
                ));
            },
        ];
    }
}
