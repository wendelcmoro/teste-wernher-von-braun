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
            'name' => 'teste device',
            'description' => 'Teste de dispositivo, descrito por ....',
            'Manufacturer' => 'Desconhecido',
            'access_url' => 'http://localhost:8000/',
            ]
        );
    }
}
