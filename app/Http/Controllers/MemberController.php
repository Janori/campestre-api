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
        $member = Member::take($count)->skip($from)->get();
        $q = Member::count('*');
        return response()->json(JResponse::set(true,$q, $member));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        try {
            $member = Member::create($request->all());   
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
        if(is_null($id) || !is_numeric($id)) return JResponse::set(false, 'Error en la petición.');
        $member = Member::find($id);
        if(is_null($member)){
            return JResponse::set(false, 'El usuario seleccionado no existe');
        }
        foreach ($request->all() as $key => $value)
            if(!is_null($value) &&  in_array(strtolower($key), $valid))
                $member->{$key} = $value;
        if($request->all()->indexOf('info') > -1){
            return 'hola';
            if($member->members_rel == null){
                $info = new MembersRel($request->all()['info']);
            }else{
                $info = $member->members_rel;
                foreach ($info as $key => $value)
                    if(!is_null($value))
                        $info->{$key} = $value;    
            }
            $info->id_member = $member->id;
        }
        if(in_array('data', $request->all())){
            if($member->members_data == null){
                $data = new MembersData($request->all()['data']);
            }else{
                $data = $member->members_data;
                foreach ($data as $key => $value)
                    if(!is_null($value))
                        $data->{$key} = $value;    
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
