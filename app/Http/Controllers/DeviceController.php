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
            $device = Device::where('identifier', $identifier)
                ->with('deviceCommands.deviceCommandParams')
                ->where('manufacturer', 'PredictWeater')
                ->whereHas(
                    'deviceCommands', function ($query) {
                            $query->where('command', 'get_rainfall_intensity');
                    }
                )->first();

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

        $devices = Device::with('deviceCommands.deviceCommandParams')
            ->where('manufacturer', 'PredictWeater')
            ->whereHas(
                'deviceCommands', function ($query) {
                    $query->where('command', 'get_rainfall_intensity');
                }
            )->get();
        
        // Realiza a requisição telnet para o dispositivo
        // utilizando o comando 'rainfall_intensity'
        foreach ($devices as $device) {
            // Define as informações de conexão Telnet
            $host = $device->access_url;
            $port = 12000;

            // Abre uma conexão Telnet
            $socket = fsockopen($host, $port, $errno, $errstr, 10);

            if (!$socket) {
                return response()->json(
                    [
                        'description' => "Erro ao conectar ao dispositivo. Telnet: $errstr ($errno)",
                    ],
                    500
                ); 
            }

            // Envia o comando Telnet para obter a intensidade da chuva
            $command = $device->deviceCommands[0]->command;
            fwrite($socket, $command);

            // Lê a resposta do servidor Telnet
            $response = '';
            while (!feof($socket)) {
                $response .= fgets($socket, 128);
            }

            // Fecha a conexão Telnet
            fclose($socket);
            
            // Para cada dispositivo, inclui um campo com a densidade da chuva daquele dispositivo
            $device->rainfall_intensity = $response;
        }

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

        if ($request->manufacturer != 'PredictWeater') {
            return response()->json(
                [
                'description' => 'A solicitação não foi realizada pelo proprietário do dispositivo',
                ],
                404
            );            
        }

        $device = null;
        if ($request->isMethod('put')) {
            $device = Device::where('identifier', $request->identifier)->first();
            if (!$device) {
                return response()->json(
                    [
                    'description' => 'Dispositivo não encontrado',
                    ],
                    404
                );
            }

            if ($device->manufacturer != 'PredictWeater') {
                return response()->json(
                    [
                    'description' => 'A solicitação não foi realizada pelo proprietário do dispositivo',
                    ],
                    401
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

        $device = Device::where('identifier', $request->identifier);
        if (!$device) {
            return response()->json(
                [
                'description' => 'Dispositivo não encontrado',
                ],
                404
            );
        }

        if ($device->manufacturer != 'PredictWeater') {
            return response()->json(
                [
                'description' => 'A solicitação não foi realizada pelo proprietário do dispositivo',
                ],
                401
            ); 
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
