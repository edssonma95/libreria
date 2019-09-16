<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\JwtAuth;


class LoginController extends Controller
{
    public function loginView()
    {
        return view('pages.login');
    }
    public function login(Request $request)
    {
        $jwtAuth = new \JwtAuth();
        
        $json = json_encode($request->get("credentials", null));
        $params = json_decode($json);
        $params_array = json_decode($json, true);
        
        $validate = \Validator::make($params_array, [
            'user'     => 'required', //unique email
            'pwd'  => 'required'
            ]);
            
        if ($validate->fails()) {
            $signup = array(
                "status" => "error",
                "code" => "400",
                "message" => "User cannot be log in...",
                'errors' => $validate->errors(),
            );
        } else {
            $password = hash('sha256', $params->pwd);        
            $signup = $jwtAuth->signup($params->user, $password);

            if(isset($params->gettoken)){
                $signup = $jwtAuth->signup($params->user, $password, true);
            }
        }

        return response()->json($signup);
     
    }
    public function logout()
    {
        $jwtAuth = new JwtAuth();
        $decoded = json_decode(json_encode($jwtAuth->decode(session('remember_token'))), true);
        $user = User::find($decoded['sub']);
        session()->flush();
        return redirect('index');
    }
}
