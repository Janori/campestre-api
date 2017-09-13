<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Helpers\JResponse;
use App\Models\Member;
use App\Models\MembersData;
use App\Models\MembersRel;

use Input;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $from = Input::get('from', 0);
        $count = Input::get('count', 10);
        $member = Member::where('tipo', '<>', 'E')->take($count)->skip($from)->get()->toArray();
        $q =  Member::where('tipo', '<>', 'E')->count();
        return response()->json(JResponse::set(true,'[obj]', $member))->header('RowCount',$q);
    }
    public function employees(){
        $from = Input::get('from', 0);
        $count = Input::get('count', 10);
        $member = Member::where('tipo', 'E')->take($count)->skip($from)->get()->toArray();
        $q = Member::where('tipo', 'E')->count();
        return response()->json(JResponse::set(true,'[obj]', $member))->header('RowCount',$q);   
    }
    public function guests(){
        $from = Input::get('from', 0);
        $count = Input::get('count', 10);
        $member = Member::where('tipo', 'I')->take($count)->skip($from)->get()->toArray();
        $q = Member::where('tipo', 'I')->count();
        return response()->json(JResponse::set(true,'[obj]', $member))->header('RowCount',$q);   
    }
    public function associates(){
        $from = Input::get('from', 0);
        $count = Input::get('count', 10);
        $member = Member::where('tipo', 'A')->take($count)->skip($from)->get()->toArray();
        $q = Member::where('tipo', 'A')->count();
        return response()->json(JResponse::set(true,'[obj]', $member))->header('RowCount',$q);   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $info = null;
        $data = null;
        foreach ($request->all() as $key => $value){
            if(strtolower($key) === 'info'){
                $info = new MembersRel($value);
            }else if(strtolower($key) === 'data'){
                $data = new MembersData($value);
            }
        }
        try {
            $member = Member::create($request->all());   
            $info->id_member = $member->id;
            if(! $info->id_ref){
                $info->id_ref = $member->id;
            }

            $data->id_member = $member->id;

            $info->save();
            $data->save();
        } catch (\Exception $e) {
            /*if($e->getCode() == 23000){
                return response()->json(JResponse::set(false,'El usuario ya existe.'));
            }*/
            return response()->json(JResponse::set(false,'No se pudo crear el usuario.', $e->getMessage()));
        }
        return response()->json(JResponse::set(true,'obj', $member->toArray()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $valid = ['nombre','tipo'];
        $info = null;
        $data = null;
        $req = $request->all();
        if(is_null($id) || !is_numeric($id)) return JResponse::set(false, 'Error en la petición.');
        $member = Member::find($id);
        if(is_null($member)){
            return JResponse::set(false, 'El usuario seleccionado no existe');
        }
        foreach ($request->all() as $key => $value){
            if(strtolower($key) === 'info'){
                $info = $value;
            }else if(strtolower($key) === 'data'){
                $data = $value;
            }
            if(!is_null($value) &&  in_array(strtolower($key), $valid))
                $member->{$key} = $value;
        }

        if($info){
            if($member->members_rel == null){
                $info = new MembersRel($info);
            }else{
                $info2 = $member->members_rel;
                foreach ($info as $key => $value)
                    if(!is_null($value))
                        $info2->{$key} = $value;    
                $info = $info2;
            }
            $info->id_member = $member->id;
        }
        if($data){
            if($member->members_data == null){
                $data = new MembersData($data);
            }else{
                $data2 = $member->members_data;
                foreach ($data as $key => $value)
                    if(!is_null($value))
                        $data2->{$key} = $value; 
                $data = $data2;   
            }
            $data->id_member = $member->id;
        }
        try{
            $member->save();   
            if(!is_null($data)){
                $data->save();   
            }
            if(!is_null($info)){
                $info->save(); 
            }
            return JResponse::set(true, 'obj', $member->toArray());
        }catch(\Exception $e){
            return JResponse::set(false, 'El conjunto de datos enviados no son válidos.', $e);
        }
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
