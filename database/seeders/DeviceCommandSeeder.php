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
            'command' => 'teste comando',
            'device_id' => 1,
            ]
        );
    }
}
