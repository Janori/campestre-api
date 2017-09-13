<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Input;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Helpers\JResponse;

class UserController extends Controller
{
    public function employees(){
        //if(User::HasPermission('v_cat_prov')){
            $from = Input::get('from', 0);
            $count = Input::get('count', 10);
            $users = User::where('kind', 'p')->take($count)->skip($from)->get();
            $q = User::where('kind', 'p')->count('*');
            return response()->json(JResponse::set(true,$q, $users));
        //}
        //return response()->json(JResponse::set(false, 'El usuario no tiene permiso para accesar a esta sección'));
    }
/*
    public function kinds(){
        return response()->json(JResponse::set(true, 'enum', User::$kind_options));
    }
*/

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $from = Input::get('from', 0);
        $count = Input::get('count', 10);
        $users = User::take($count)->skip($from)->get();
        //$users = User::where('kind','<>','p')->take($count)->skip($from)->get();
        //$q = User::where('kind','<>','p')->count('*');
        return response()->json(JResponse::set(true,'[obj]', $users->toArray()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        try {
            $user = User::create($request->all());   
        } catch (\Exception $e) {
            /*if($e->getCode() == 23000){
                return response()->json(JResponse::set(false,'El usuario ya existe.'));
            }*/
            return response()->json(JResponse::set(false,'No se pudo crear el usuario.', $e->getMessage()));
        }
        return response()->json(JResponse::set(true,'obj', $user->toArray()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        if(is_null($id) || !is_numeric($id)) return JResponse::set(false, 'Error en la petición.');
        $user = User::find($id);
        if(is_null($user) || $user->active == false){
            return JResponse::set(false, 'El usuario seleccionado no existe');
        }else{
            return JResponse::set(true, 'obj', $user->toArray());
        }
    }

    public function search($by, $value = null){
        if(is_null($value)) return response()->json(JResponse::set(false, 'No se ha proporcionado ningun valor de busqueda.'));
        try{
            $user = User::where($by, $value)->get()->first();
            if($user)
                return response()->json(JResponse::set(true, 'obj', $user->toArray()));   
            else return response()->json(JResponse::set(false, 'Usuario no encontrado'));   
        }catch(\Exception $e){
            return JResponse::set(false, 'El conjunto de datos enviados no son válidos.', $e);
        }
    }

    public function advancedSearch($kind){
        $from = Input::get('from', 0);
        $count = Input::get('count', 10);

        $values = [
            'name' => Input::get('name', null),
            'email' => Input::get('email', null),   
            'username' => Input::get('username', null)
        ];

        $user = User::where('kind', $kind)->where(function($q) use ($values){
            foreach($values as $key => $value){
                if(!is_null($value)){
                    $q->orWhere($key, 'like', "%".$value."%");
                }
            }
            return $q;
        });

        
        $q = $user->count('*');
        $users = $user->take($count)->skip($from)->get();
        return JResponse::set(true, $q, $users);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        if(is_null($id) || !is_numeric($id)) return JResponse::set(false, 'Error en la petición.');
        $user = User::find($id);
        if(is_null($user)){
            return JResponse::set(false, 'El usuario seleccionado no existe');
        }
        foreach ($request->all() as $key => $value)
            if(!is_null($value) && $key != "username")
                $user->{$key} = $value;
        try{
            $user->save();   
            return JResponse::set(true, 'obj', $user->toArray());
        }catch(\Exception $e){
            return JResponse::set(false, 'El conjunto de datos enviados no son válidos.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        if(is_null($id) || !is_numeric($id)) return JResponse::set(false, 'Error en la petición.');
        $user = User::find($id);
        if($user)
            $user->delete($id);
        /*$user = User::find($id);
        if(is_null($user)){
            return JResponse::set(false, 'El usuario seleccionado no existe');
        }
        $user->active = false;
        try{
            $user->save();   
            return JResponse::set(true, 'obj', $user->toArray());
        }catch(\Exception $e){
            return JResponse::set(false, 'El conjunto de datos enviados no son válidos.');
        }*/
    }
}


















