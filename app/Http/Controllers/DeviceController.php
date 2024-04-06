<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Validator;

use App\Models\Device;

class DeviceController extends Controller
{
    public function getDevices(Request $request)
    {
        $devices = Device::all();

        return response()->json(
            [
            'success' => true,
            'devices' => $devices,
            ],
            200
        );        
    }

}
