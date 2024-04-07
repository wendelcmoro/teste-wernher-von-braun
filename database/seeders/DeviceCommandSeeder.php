<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use DB;

class DeviceCommandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('device_commands')->insert(
            [
            'command' => 'get_rainfall_intensity',
            'operation' => 'get_rainfall_intensity',
            'description' => 'Mede a densidade de chuva',
            'result' => 'Densidade da chuva',
            'format' => 'Application/Json',
            'device_id' => 1,
            ]
        );
    }
}
