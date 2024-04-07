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
            'result' => 'Uma mensagem indicando o volume de chuvas, por ex: "A intensidade da chuva é 10 mm/h\r\n"',
            'format' => 'Texto simples',
            'device_id' => 1,
            ]
        );
    }
}
