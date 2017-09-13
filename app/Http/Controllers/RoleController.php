<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\JResponse;

use App\Models\Role;
use App\User;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $roles = Role::all();
        return response()->json(JResponse::set(true,'[obj]', $roles));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $roles = Role::find(1);
        if(is_null($id) || !is_numeric($id)) return Response::set(false, 'Error en la petición.');
        
        if(is_null($roles) || $roles->active == false){
            return JResponse::set(false, 'El rol seleccionado no existe');
        }else{
            return JResponse::set(true, 'obj', ''.$roles);
        }
        return '';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
