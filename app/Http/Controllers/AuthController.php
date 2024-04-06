<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Validator;

use App\Models\User;

class AuthController extends Controller
{
    public function postLogin(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
            'username'       => 'required|max:255',
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


        $user = User::where('name', $request->username)->first();

        if ($user) {
            return response()->json(
                [
                'user' => $user,
                'auth_token'   => $user->createToken($user->id)->plainTextToken
                ],
                200
            );
        }

        return response()->json(
            [
            'message' => 'User not found'
            ],
            401
        );
    }

}
