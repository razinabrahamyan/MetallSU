<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Position;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class NewProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $item_shablons = [
            [
                'position' => 'Кухня',
                'items' => [
                    [
                        'item' => 'чай',
                        'parent' => null
                    ], [
                        'item' => 'кофе',
                        'parent' => null
                    ], [
                        'item' => 'посуда',
                        'parent' => null
                    ], [
                        'item' => 'сахар',
                        'parent' => null
                    ], [
                        'item' => 'мус.пакет',
                        'parent' => null
                    ], [
                        'item' => 'салфетки',
                        'parent' => null
                    ], [
                        'item' => 'влажная',
                        'parent' => 'салфетки'
                    ], [
                        'item' => 'сухая',
                        'parent' => 'салфетки'
                    ], [
                        'item' => 'микров',
                        'parent' => null
                    ], [
                        'item' => 'чайник',
                        'parent' => null
                    ], [
                        'item' => 'кулер',
                        'parent' => null
                    ], [
                        'item' => 'комплект',
                        'parent' => null
                    ]
                ]
            ],
            [
                'position' => 'Зарплаты',
                'items' => [
                    [
                        'item' => 'зарплата',
                        'parent' => null
                    ],
                ]
            ],
            [
                'position' => 'Армен(рабочие/уз.)',
                'items' => [
                    [
                        'item' => 'матрас',
                        'parent' => null
                    ], [
                        'item' => 'адеала',
                        'parent' => null
                    ], [
                        'item' => 'подушки',
                        'parent' => null
                    ], [
                        'item' => 'спец-одежда',
                        'parent' => null
                    ], [
                        'item' => 'зимний',
                        'parent' => 'спец-одежда'
                    ], [
                        'item' => 'летний',
                        'parent' => 'спец-одежда'
                    ], [
                        'item' => 'перчатки',
                        'parent' => null
                    ], [
                        'item' => 'жилеты',
                        'parent' => null
                    ], [
                        'item' => 'каски',
                        'parent' => null
                    ], [
                        'item' => 'прочие',
                        'parent' => null
                    ]
                ]
            ],
            [
                'position' => 'Снабжение',
                'items' => [
                    [
                        'item' => 'электроэнергия',
                        'parent' => null
                    ], [
                        'item' => 'кондиционер',
                        'parent' => null
                    ], [
                        'item' => 'электрообог',
                        'parent' => null
                    ], [
                        'item' => 'бытовки',
                        'parent' => null
                    ], [
                        'item' => 'сантехника',
                        'parent' => null
                    ], [
                        'item' => 'туалет',
                        'parent' => null
                    ], [
                        'item' => 'мусор',
                        'parent' => null
                    ], [
                        'item' => 'стройки',
                        'parent' => null
                    ]
                ]
            ],
            [
                'position' => 'Охрана',
                'items' => [
                    [
                        'item' => 'инструменты',
                        'parent' => null
                    ], [
                        'item' => 'редуктор кис',
                        'parent' => null
                    ], [
                        'item' => 'редуктор пропан',
                        'parent' => null
                    ], [
                        'item' => 'шланги х',
                        'parent' => null
                    ], [
                        'item' => 'хамуты',
                        'parent' => null
                    ], [
                        'item' => 'изолента',
                        'parent' => null
                    ], [
                        'item' => 'ножы',
                        'parent' => null
                    ], [
                        'item' => 'резаки',
                        'parent' => null
                    ]
                ]
            ],
            [
                'position' => 'ГСМ',
                'items' => [
                    [
                        'item' => 'солярка',
                        'parent' => null
                    ], [
                        'item' => 'бензин',
                        'parent' => null
                    ], [
                        'item' => 'масло смаз',
                        'parent' => null
                    ], [
                        'item' => 'масло матор',
                        'parent' => null
                    ], [
                        'item' => 'масло гидр',
                        'parent' => null
                    ], [
                        'item' => 'масло?',
                        'parent' => null
                    ], [
                        'item' => 'антифриз',
                        'parent' => null
                    ], [
                        'item' => 'незамерзайка',
                        'parent' => null
                    ]
                ]
            ],
            [
                'position' => 'ВОДИТЕЛИ',
                'items' => [
                    [
                        'item' => 'транспондер',
                        'parent' => null
                    ], [
                        'item' => 'парковка',
                        'parent' => null
                    ], [
                        'item' => 'мойка',
                        'parent' => null
                    ], [
                        'item' => 'штраф',
                        'parent' => null
                    ], [
                        'item' => 'гаи',
                        'parent' => null
                    ], [
                        'item' => 'шиномонтаж',
                        'parent' => null
                    ], [
                        'item' => 'завтрак',
                        'parent' => null
                    ], [
                        'item' => 'обед',
                        'parent' => null
                    ],[
                        'item' => 'ужин',
                        'parent' => null
                    ],

                ]
            ],
            [
                'position' => 'Инструменты',
                'items' => [
                    [
                        'item' => 'болгарка',
                        'parent' => null
                    ], [
                        'item' => 'кусачки',
                        'parent' => null
                    ], [
                        'item' => 'большие',
                        'parent' => 'кусачки'
                    ], [
                        'item' => 'маленькие',
                        'parent' => 'кусачки'
                    ], [
                        'item' => 'тросорезы',
                        'parent' => null
                    ], [
                        'item' => 'большие',
                        'parent' => 'тросорезы'
                    ], [
                        'item' => 'маленькие',
                        'parent' => 'тросорезы'
                    ], [
                        'item' => 'счетки металические',
                        'parent' => null
                    ]

                ]
            ],
            [
                'position' => 'ВИДЕОНАБЛЮДЕНИЕ ЛЕОН',
                'items' => [
                    [
                        'item' => 'камера - регистраторы',
                        'parent' => null
                    ], [
                        'item' => 'кабель',
                        'parent' => null
                    ], [
                        'item' => 'комплектующие-расходники',
                        'parent' => null
                    ], [
                        'item' => 'инструменты',
                        'parent' => null
                    ]

                ]
            ],
            [
                'position' => 'СЕРВЕРНАЯ ОРГТЕХНИКА',
                'items' => [
                    [
                        'item' => 'компютер',
                        'parent' => null
                    ], [
                        'item' => 'монитор',
                        'parent' => null
                    ], [
                        'item' => 'юпс',
                        'parent' => null
                    ], [
                        'item' => 'роутер',
                        'parent' => null
                    ], [
                        'item' => 'клавиатура',
                        'parent' => null
                    ], [
                        'item' => 'мышь',
                        'parent' => null
                    ], [
                        'item' => 'наушники',
                        'parent' => null
                    ], [
                        'item' => 'коврики',
                        'parent' => null
                    ], [
                        'item' => 'носители',
                        'parent' => null
                    ], [
                        'item' => 'комплектуюие',
                        'parent' => null
                    ]

                ]
            ],
        ];

        foreach ($item_shablons as $item_shablon){
            $shablon_cat = Position::where('title',$item_shablon['position'])->first();
            foreach($item_shablon['items'] as $item){
                $item_create = new Item();
                $item_create ->position_id = $shablon_cat->id;
                if($item['parent']){
                    $parent = Item::where('title',$item['parent'])->first();
                    $item_create->parent_id = $parent->id;
                }
                $item_create ->title = $item['item'];
                $item_create ->save();
            }

        }
    }
}
