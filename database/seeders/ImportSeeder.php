<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $import = 'database/import/ch16265_sagamet.sql';
        DB::unprepared(file_get_contents($import));
        $this->command->info('Import Success!');
    }
}
