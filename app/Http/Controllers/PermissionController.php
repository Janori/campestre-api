<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthenticateController;
use App\Http\Middleware\VerifyJWTToken;
use App\Helpers\JResponse;
use App\Models\Permission;
use App\User;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $perms = Permission::all();
        return response()->json(JResponse::set(true,'[obj]', $perms->toArray()));
    }

    public function getPerms(){
        $user = AuthenticateController::getUserFromToken(VerifyJWTToken::$token);
        $perms = $user->role->permissions;
        return response()->json(JResponse::set(true,'[obj]', $perms->toArray()));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $user = AuthenticateController::getUserFromToken(VerifyJWTToken::$token);
        
        foreach($user->role->permissions as $perm){
            if($perm['code'] == $id){
                return response()->json(JResponse::set(true,'bool', true));
            }
        }
        return response()->json(JResponse::set(true,'bool', false));
    }

}









