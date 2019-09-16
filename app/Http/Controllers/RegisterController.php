<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        //get data
        $json = $request->input('json', null);
        $params = json_decode($json); //object
        $params_array = json_decode($json, true); //array

        if (!empty($params) && !empty($params_array)) {

            //clean data
            $params_array = array_map('trim', $params_array);

            //validate
            $validate = \Validator::make($params_array, [
                'name'      => 'required|alpha',
                'username'  => 'required|alpha',
                'email'     => 'required|email|unique:users', //unique email
                'password'  => 'required',
                'rol_id'    => 'required'
            ]);

            if ($validate->fails()) {
                $data = array(
                    "status" => "error",
                    "code" => "400",
                    "message" => "User cannot be created...",
                    'errors' => $validate->errors(),
                );
            } else {
                //hash password
                $password = hash('sha256', $params->password);
                //create user
                $user = new User();
                $user->name = $params_array['name'];
                $user->username = $params_array['username'];
                $user->email = $params_array['email'];
                $user->password = $password;
                $user->rol_id = $params_array['rol_id'];
                $user->save();

                $data = array(
                    "status" => "success",
                    "code" => "200",
                    "message" => "User created...",
                );
            }
        }else{
            $data = array(
                "status" => "error",
                "code" => "400",
                "message" => "Invalid data..."
            );
        }

        return response()->json($data);
    }
}
