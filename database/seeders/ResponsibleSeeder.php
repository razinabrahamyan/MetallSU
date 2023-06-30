<?php

namespace Database\Seeders;

use App\Models\DefaultResponsible;
use App\Models\Item;
use App\Models\Responsible;
use App\Models\ResponsibleType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ResponsibleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // id = 1
        $responsible_type = new ResponsibleType();
        $responsible_type -> name = 'Ответственный';
        $responsible_type -> save();

        // id = 2
        $responsible_type = new ResponsibleType();
        $responsible_type -> name = 'Площадка';
        $responsible_type -> save();

        // id = 3
        $responsible_type = new ResponsibleType();
        $responsible_type -> name = 'Водитель';
        $responsible_type -> save();

        // id = 4
        $responsible_type = new ResponsibleType();
        $responsible_type -> name = 'Машина';
        $responsible_type -> save();

        $templates = [
            ['type_id' => 2, 'name' => 'Цветной'],
            ['type_id' => 2, 'name' => 'Черный'],
            ['type_id' => 2, 'name' => 'Бухгалтерия'],
            ['type_id' => 2, 'name' => 'Ж/Д Площадка'],
        ];
        $defaults = [

        ];
        foreach ($templates as $template){
            $responsible = new  Responsible();
            $responsible -> name = $template['name'];
            $responsible -> type_id = $template['type_id'];
            $responsible -> save();
        }
        foreach ($defaults as $default){
            $conn = new  DefaultResponsible();
            $conn -> item_id = Item::where('title',$default['item'])->first()->id;
            $conn -> responsible_id = Responsible::where('name',$default['responsible'])->first()->id;
            $conn -> save();
        }

    }
}
