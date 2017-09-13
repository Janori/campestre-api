<?php

namespace App\Http\Controllers;

use Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Helpers\JResponse;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthenticateController extends Controller
{
    private $expirationTime = 60;

    public function authenticate(Request $request){
        if($request->has('email')){
            $credentials = $request->only('email', 'password');   
        }else{
            $credentials = $request->only('username', 'password');   
        }
        try{
            if(!$token = JWTAuth::attempt($credentials)){
                return response()->json(JResponse::set(false, 'invalid credentials')); //,401
            }
        }catch(JWTException $e){
            return response()->json(JResponse::set(false, 'could not create token')); //,500
        }
        $user = AuthenticateController::getUserFromToken($token);
        return response()->json(JResponse::set(true,'Token successfully created', [
            'token' => $token, 
            'ttl' => $this->expirationTime, 
            'user' => $user->toArray(),
            'perms' => ($user->role === null ? '' : $user->role->permissions)
            ]));
    }

    public function register(Request $request){
        $user = User::create([
          'name' => $request->get('name'),
          'email' => $request->get('email'),
          'password' => bcrypt($request->get('password'))
        ]);
        return response()->json(JResponse::set(true,'User created successfully',$user));
    }
    
    public function isLogged(Request $request){
        $auth = $request->header('Authorization');
        if(is_null($auth) || $auth == "") return response()->json(JResponse::set(true,'bool',false));
        try{
            $user = JWTAuth::toUser($auth);
            if($user) return response()->json(JResponse::set(true,'bool',true));
            return response()->json(JResponse::set(true,'bool',false));    
        }catch(\Exception $ex){
            return response()->json(JResponse::set(true,'bool',false));    
        }
        
    }
    public static function getUserFromToken($token){
        $user = JWTAuth::toUser($token);
        return $user;
    }

}