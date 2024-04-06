<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use DB;

class DeviceCommandDescriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('device_command_descriptions')->insert(
            [
            'op_description' => 'teste',
            'description' => 'teste',
            'expected_response' => 'teste',
            'data_format' => 'teste',
            'device_command_id' => 1,
            ]
        );
    }
}
