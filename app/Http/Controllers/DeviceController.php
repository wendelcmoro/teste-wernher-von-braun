<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Validator;

use App\Models\Device;

class DeviceController extends Controller
{
    public function getDevices($identifier = null)
    {
        if ($identifier) {
            $device = Device::where('identifier', $identifier)->with('deviceCommands.deviceCommandParams')->first();

            if ($device) {
                return response()->json(
                    [
                    'device' => $device,
                    ],
                    200
                ); 
            }

            return response()->json(
                [
                'description' => 'Dispositivo não encontrado',
                ],
                404
            );
        }

        $devices = Device::with('deviceCommands.deviceCommandParams')->get();

        return response()->json(
            [
            'devices' => $devices,
            ],
            200
        );        
    }

    public function storeDevice(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
            'identifier'        => 'required|max:255',
            'name'              => 'required|max:255',
            'description'       => 'required|max:255',
            'manufacturer'      => 'required|max:255',
            'access_url'        => 'required|url|max:255',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                'msg'   => 'Erros de validação',
                'erros' => $validator->errors()
                ],
                500
            );
        }

        if ($request->isMethod('put')) {
            $device = Device::find($request->identifier);
            if (!$device) {
                return response()->json(
                    [
                    'description' => 'Dispositivo não encontrado',
                    ],
                    404
                );
            }
        }
        else {
            $device = new Device;
            $device->identifier = $request->identifier;
        }

        $device->name =$request->name;
        $device->description = $request->description;
        $device->manufacturer = $request->manufacturer;
        $device->access_url = $request->access_url;

        $device->save();

        if ($request->isMethod('put')) {
            return response()->json(
                [
                'description' => 'Requisição realizada com sucesso',
                'device' => $device
                ],
                201
            );
        }

        return response()->json(
            [
            'description' => 'Requisição realizada com sucesso',
            ],
            201
        )->header('Location', url('/').'/api/device/'.$device->identifier);
    }

    public function deleteDevice(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
            'identifier'              => 'required|integer',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                'msg'   => 'Erros de validação',
                'erros' => $validator->errors()
                ],
                500
            );
        }

        $device = Device::find($request->identifier);
        if (!$device) {
            if (!$device) {
                return response()->json(
                    [
                    'description' => 'Dispositivo não encontrado',
                    ],
                    404
                );
            }
        }

        $device->delete();

        return response()->json(
            [
            'description' => 'Requisição realizada com sucesso',
            'device' => $device
            ],
            201
        );
    }

}
