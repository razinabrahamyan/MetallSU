<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = new Category();
        $category->title = 'ЧЕРНЫЙ';
        $category->user_id = 1;
        $category->save();

        $category = new Category();
        $category->title = 'ГСМ';
        $category->user_id = 1;
        $category->save();

        $category = new Category();
        $category->title = 'ЦВЕТНОЙ';
        $category->user_id = 1;
        $category->save();

        $category = new Category();
        $category->title = 'ВИДЕОНАБЛЮДЕНИЕ ЛЕОН';
        $category->user_id = 1;
        $category->save();

        $category = new Category();
        $category->title = 'СЕРВЕРНАЯ ОРГТЕХНИКА';
        $category->user_id = 1;
        $category->save();


        $arr = [
       '     /cost',
       ' /cost/lazyLoad?draw',
       '     /get-statistic-rows',
       '     /get-tree-info-filtered',
       '     /get-chart-info',
       '     /cost-store',
       '     /lists',
       '     /lists/1',
       '     /list-update',
       '     /responsible/40',
       '     /get-responsibles-filtered',
       '     /search?text',
           ' /cost-edit',
           ' /delete-cost',
           ' /cost-update',
       '         /list-add',
       '         /cost-items',
       '         /items-update',
       '         /edit-groups',
       '         /update-groups',
       '         /outcome',
       '         /outcome-export',
       '         /edit-export-groups',
       '         /update-export-groups',
       '         /logistics',
       '         /logistics/lazy-load?draw',
       '             /product-item/56',
       '             /category/52',
       '             /get-category-filtered',
       '             /get-items-filtered',
'            /store',
'        /create-store',
'        /store-items/search',
'        /add-elem-to-store',
'        /store/11',
'        /deduct-item',
'        /deduction/5521',
'        /deduction/update',
'        /storage-history/1',
'        /set-history-filter?filter=deduct',
'        /storage-calculate/1',
'        /storage-get-driver-data',
'        /user/settings/id?2',
'        /user/settings/update/id?2',
'            /user/new',
'/user/create',
'            /users/list',
        ];

    }
}
