<?php

namespace Database\Seeders;

use App\Models\Deduction;
use App\Models\ItemStorage;
use App\Models\StoreItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StorageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $storage = new ItemStorage();
        $storage ->title = 'Склад' ;
        $storage ->user_id = 2 ;
        $storage ->save();

        $store_items = [
            [
                'name' => 'амортизатор',
                'description' => 'валдай'
            ],
            [
                'name' => 'Масло Shell R6',
                'description' => 'Маз . КамАЗ'
            ],
            [
                'name' => 'Гидравлика 46',
                'description' => 'Фукс'
            ],
            [
                'name' => 'AdBlue',
                'description' => 'Мочевина'
            ],
            [
                'name' => 'ATF III Роснефть',
                'description' => 'Рулевая'
            ],
            [
                'name' => 'Смазка EP 2 BLUE',
                'description' => 'На ходовой ( в тюбик )'
            ],
            [
                'name' => 'Фильтр W 11 102/36',
                'description' => 'Масло - МАЗ'
            ],
            [
                'name' => 'Фильтр LF 16352',
                'description' => 'Масло - Валдай'
            ],
            [
                'name' => 'Резины стабилизатора перед Валдай',
                'description' => 'новый'
            ],
            [
                'name' => 'Колодки Валдай',
                'description' => 'новый'
            ],
            [
                'name' => 'Компрессор Валдай',
                'description' => 'Воздух - МАЗ'
            ],
            [
                'name' => 'Дворники',
                'description' => 'новый'
            ],

        ];

        $tos = ['411','777','888', '101', '558'];
        for ($i = 0; $i < 12; $i++){
            $item = new StoreItem();
            $item ->name = $store_items[$i]['name'];
            $item ->description = $store_items[$i]['description'];
            $item ->storage_id = 1;
            $item ->price = rand(20,150);
            $item ->count = rand(5,30);
            $item ->save();
        }
        for ($i = 0; $i < 5000; $i++){
            $check = rand(0,1);
            $deduction = new Deduction();


            switch ($check){
                case 0: $deduction->to = $tos[rand(0,4)];
                    $deduction ->comment = Str::random(rand(15,60));
                    $deduction ->type = 'deduction';
                    break;
                case 1: ;
                    $deduction ->type = 'addition';
                    break;
            }
            $deduction ->count = rand(5,40);
            $deduction ->storage_id = 1;
            $deduction ->item_id = rand(1,12);
            $deduction ->date = date("Y-m-d h:i:s",mktime(date('h') + rand(1,6), 0, 0, date('m'), date('d') + rand(1,5), date('Y')));
            $deduction ->save();
        }
    }
}
