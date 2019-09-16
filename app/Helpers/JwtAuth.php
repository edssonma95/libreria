<?php
    namespace App\Helpers;

    use Firebase\JWT\JWT;
    use Illuminate\Support\Facades\DB;
    use App\Models\User;
    use Doctrine\Instantiator\Exception\UnexpectedValueException;
    use DomainException;

class JwtAuth{
        
        public $key;

        public function __construct()
        {
            $this->key = 'clavedetoken';
        }

        public function signup($username, $password, $gettoken = null)
        {

            $user = User::where([
                'username' => $username,
                'password' => $password
            ])->first();

            $signup = false;
            if(is_object($user)){
                $signup = true;
            }

            if($signup){
                $token = array(
                    'sub' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'iat' => time(), //cuando se crea el token
                    'exp' => time() + (7 * 24 * 60 * 60) //cuando caduca | caduca en una semana
                );

                
                $jwt = JWT::encode($token, $this->key, 'HS256');
                $decoded = JWT::decode($jwt, $this->key, ['HS256']);

                session(['remember_token' => $jwt]);
                // $user->update(['remember_token' => $jwt]);

                if(is_null($gettoken)){
                    $data =  $jwt;
                }else{
                    $data =  $decoded;
                }

                $data = array(
                    "status" => "success",
                    "code" => "200",
                    "message" => "User logged in correctly.",
                    "data" => $data
                ); 
            }
            else{
                $data = array(
                    "status" => "error",
                    "code" => "400",
                    "message" => "Invalid credentials...",
                ); 
            }

            return $data;
        }

        public function checkToken($jwt, $getIdentity = false){
            $auth = false;
            $decoded = null;

            try{
                $decoded = JWT::decode($jwt, $this->key, ['HS256']);
            }
            catch(UnexpectedValueException $e){
                $auth = false;

            }catch(DomainException $e){
                $auth = false;
            }

            if(!empty($decoded) && is_object($decoded) && isset($decoded->sub)){
                $auth = true;
            }else{
                $auth = false;
            }

            if($getIdentity){
                return $decoded;
            }

            return $auth;
        }

        public function decode($jwt)
        {
            return  JWT::decode($jwt, $this->key, ['HS256']);
        }
    }
