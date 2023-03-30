<?php

use Illuminate\Database\Seeder;

class CronSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file_handle = fopen(public_path('FUNCIONA_CRON PLISSSS.TXT'), 'a+');
    }
}
