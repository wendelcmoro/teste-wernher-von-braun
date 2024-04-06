<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use DB;

class DeviceCommandParamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('device_command_params')->insert(
            [
            'name' => 'teste',
            'description' => 'teste',
            'device_command_id' => 1,
            ]
        );
    }
}
