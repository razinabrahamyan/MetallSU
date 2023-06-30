<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\WorkerEventType;
use App\Models\WorkerStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = new WorkerStatus();
        $status->status = 'работает';
        $status->save();

        $status = new WorkerStatus();
        $status->status = 'в отпуске';
        $status->save();

        $status = new WorkerStatus();
        $status->status = 'уволен';
        $status->save();

        $event_status = new WorkerEventType();
        $event_status->type = 'принятие на работу';
        $event_status->save();

        $event_status = new WorkerEventType();
        $event_status->type = 'уволнение';
        $event_status->save();

        $event_status = new WorkerEventType();
        $event_status->type = 'Смена отдела';
        $event_status->save();


        $post = new Post();
        $post->title = 'Менеджер';
        $post->save();

        $post = new Post();
        $post->title = 'Водитель';
        $post->save();

        $post = new Post();
        $post->title = 'Программист';
        $post->save();

        $post = new Post();
        $post->title = 'Разнорабочий';
        $post->save();




    }
}
