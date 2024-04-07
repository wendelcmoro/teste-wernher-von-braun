<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use DB;

class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('devices')->insert(
            [
            'identifier'=> '123',
            'name' => 'Teste predictWeater',
            'description' => 'Teste predictWeater',
            'Manufacturer' => 'PredictWeater',
            'access_url' => '127.0.0.1',
            ]
        );
    }
}
