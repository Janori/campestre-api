<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Helpers\JResponse;
use App\Models\Member;
use App\Models\MembersData;
use App\Models\MembersRel;
use App\Models\MembersPagos;
use App\Visit;

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
        $w = Input::get('query', '');
        $order = Input::get('orders', null);
        $query = Member::whereIn('tipo',  ['T', 'A'])->where(function ($query) use ($w) {
                if($w != ''){
                    $query->where('nombre', 'like', '%' . $w . '%');
                    $query->orWhereHas('members_rel', function($query) use ($w){
                        if($w != ''){
                            $query->where('code', 'like', $w . '%');
                        }
                    });
                    $query->whereIn('tipo', ['T', 'A']);
                }
                      //->orWhere('', 'like', '%' . $w . '%');
            });
        $q = $query->count();
        if($order){
            $ordrs = explode(",",$order);
            foreach($ordrs as $o){
                $query->orderBy($o, 'desc');
            }
        }
        $member = $query->take($count)->skip($from)->get()->toArray();
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

    public function deleteFMD($id){
        if(is_null($id) || !is_numeric($id)) return response()->json(JResponse::set(false, 'Error en la petición.'));
        $member = Member::find($id);
        if($member->members_rel){
            $member->members_rel->fmd = '';
            $member->members_rel->save();
        }
        return response()->json(JResponse::set(true, 'Huella eliminada'));

    }


    public function unsetRel($idmember){
        if(is_null($idmember) || !is_numeric($idmember)) return response()->json(JResponse::set(false, 'Error en la petición.'));
        $member = Member::find($idmember);
        $member->tipo = 'T';
        if($member->members_rel){
            $member->members_rel->id_ref = $idmember;
            $member->members_rel->save();
        }else{
            $info = new MembersRel();
            $info->id_member = $idmember;
            $info->id_ref = $idmember;
            $info->pin = '';
            $info->rfid = '';
            $info->fmd = '';
            $info->code = '';
            $info->save();
        }
        $member->save();
        return response()->json(JResponse::set(true, 'Usuario relacionado correctamente'));
    }

    public function setRel($idmember, $idref){
        if(is_null($idmember) || !is_numeric($idmember)) return response()->json(JResponse::set(false, 'Error en la petición.'));
        if(is_null($idref) || !is_numeric($idref)) return response()->json(JResponse::set(false, 'Error en la petición.'));
        $member = Member::find($idmember);
        if($member->members_rel){
            $member->members_rel->id_ref = $idref;
            if($member->members_rel->id_ref == $member->members_rel->id_member){
                $member->tipo = 'T';
            }else{
                $member->tipo = 'A';
            }
            $member->members_rel->save();
            $member->save();
        }else{
            $info = new MembersRel();
            $info->id_member = $idmember;
            $info->id_ref = $idref;
            $info->pin = '';
            $info->rfid = '';
            $info->fmd = '';
            $info->code = '';
            $info->save();
        }

        $referencedMemeber = Member::find($idref);
        return response()->json(JResponse::set(true, 'Usuario relacionado correctamente', $referencedMemeber ? $referencedMemeber->toArray() : null));
    }


    public function relGuest($idmember, $idref){
        if(is_null($idmember) || !is_numeric($idmember)) return response()->json(JResponse::set(false, 'Error en la petición.'));
        if(is_null($idref) || !is_numeric($idref)) return response()->json(JResponse::set(false, 'Error en la petición.'));
        $member = Member::find($idmember);
        $ref = Member::find($idref);
        if($member->tipo === 'I'){
            $info = new MembersRel();
            $info->id_member = $idmember;
            $info->id_ref = $ref->members_rel->id_ref;
            $info->pin = '';
            $info->rfid = '';
            $info->fmd = '';
            $info->code = '';
            $info->save();
            return response()->json(JResponse::set(true, 'Invitado relacionado correctamente', $ref->toArray()));
        }else{
            return response()->json(JResponse::set(false, 'El usuario que se quiere relacionar no es un invitado'));
        }
    }
    public function unrelGuest($idmember, $idref){
        if(is_null($idmember) || !is_numeric($idmember)) return response()->json(JResponse::set(false, 'Error en la petición.'));
        if(is_null($idref) || !is_numeric($idref)) return response()->json(JResponse::set(false, 'Error en la petición.'));
        $member = Member::find($idmember);
        $ref = Member::find($idref);
        if($member->tipo === 'I'){
            $rel = MembersRel::where('id_member', $idmember)->where('id_ref', $ref->members_rel->id_ref);
            if($rel){
                $rel->delete();
                return response()->json(JResponse::set(true, 'Referencia removida'));
            }else{
                return response()->json(JResponse::set(false, 'La referencia que se intenta remover no existe'));
            }
        }else{
            return response()->json(JResponse::set(false, 'El usuario al que se quiere remover referencia no es un invitado'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $info = new MembersRel();
        $data = new MembersData();
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
    public function show($id) {
        return response()->json(JResponse::set(true, null, Member::find($id)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        // if(is_null($id) || !is_numeric($id)) return JResponse::set(false, 'Error en la petición.');
        // $member = Member::find($id);
        // if(is_null($member))
        //     return JResponse::set(false, 'El usuario seleccionado no existe');
        //
        // $member->fill($request->only('nombre', 'tipo'));
        //
        // if(is_null($member->members_data))
        //     $member->members_data()->associate(new MembersData($request->input('data')));
        // else
        //     $member->members_data->fill($request->input('data'));
        //
        // // if(is_null($member->members_rel))
        // //     $member->members_rel()->associate(new MembersRel($request->input('info')));
        // // else
        // //     $member->members_rel->fill($request->input('info'));
        // $member->save();
        // return JResponse::set(true, null, $member->toArray());
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

    public function lastPayment($id) {

        $lastPayment = MembersPagos::where('member_id', $id)
                                    ->orderBy('id', 'desc')
                                    ->first();

        if(is_null($lastPayment))
            return response()->json(JResponse::set(true, null, []));

        $lastDay = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        $response = [
            'paid_up'       => $lastPayment['year'] . '-' . str_pad($lastPayment['month'], 2, "0", STR_PAD_LEFT) . '-' . $lastDay[$lastPayment['month'] - 1],
            'payment_date'  => $lastPayment['payment_date']
        ];

        return response()->json(JResponse::set(true, null, $response));
    }

    public function debtors() {
        $debtors = 0;
        $members = Member::where('tipo', 'T')->get();

        foreach ($members as $member) {
            if(!isset($member->members_data) || $member->members_data->status == 'DEUDOR') // IDK wihy some members doesn't have a member_data
                continue;

            $lastPayment = $member->members_pagos()->orderBy('payment_date', 'desc')->first();

            if(is_null($lastPayment)) continue;

            $paidUp = date("Y-m-t", strtotime($lastPayment->payment_date));

            if(strtotime($paidUp) < strtotime(date('Y-m-d'))) {
                $debtors++;

                $member->members_data->status = 'DEUDOR';
                $member->members_data->save();
            }
        }

        return response()->json(JResponse::set(true, "Se ha actualizado $debtors deudores"));
    }

    public function pendingPayment() {
        $debtors = 0;
        $members = Member::where('tipo', 'T')->get();

        foreach ($members as $member) {
            if(!isset($member->members_data) || $member->members_data->status == 'PENDIENTE DE PAGO') // IDK wihy some members doesn't have a member_data
                continue;

            $lastPayment = $member->members_pagos()->orderBy('payment_date', 'desc')->first();

            if(is_null($lastPayment)) continue;

            $paidUp = date("Y-m-t", strtotime($lastPayment->payment_date));

            if(strtotime($paidUp) < strtotime(date('Y-m-d'))) {
                $debtors++;

                $member->member_data->status = 'DEUDOR';
                $member->member_data->save();
            }
        }

        return response()->json(JResponse::set(true, "Se ha actualizado $debtors deudores"));
    }

    public function payMonth(Request $request) {
        try {
            $payment = MembersPagos::create($request->all());
            $lastDay = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
            $response = [
                'paid_up'       => $payment['year'] . '-' . str_pad($payment['month'], 2, "0", STR_PAD_LEFT) . '-' . $lastDay[$payment['month'] - 1],
                'payment_date'  => $payment['payment_date']
            ];

            $member = Member::find($request->input('member_id'));
            $member->data->status = 'Activo';
            $member->save();

            return response()->json(JResponse::set(true, 'Pago registrado con éxito', $response));

        } catch(\Exception $e) {
            return response()->json(JResponse::set(false, $e->getMessage()));
        }
    }

    public function getHosts($idmember) {
        try {
            $member = Member::find($idmember);


            $hosts = Member::whereIn('id', function($query) use ($member) {
                $query->select('id_ref')
                      ->from(with(new MembersRel)->getTable())
                      ->where('id_member', $member->id)
                      ->where('id_ref', '<>', $member->id);
            })->get();

            return response()->json(JResponse::set(true, null, $hosts->toArray()));
        } catch(\Exception $e) {
            return response()->json(JResponse::set(false, $e->getMessage()));
        }
    }

    public function checkVisits($member_id) {
        $month  = date('n');
        $year   = date('Y');
        $visitas = DB::table(with(new Visit)->getTable())
                        ->whereRaw(sprintf('MONTH(date) = %s', $month))
                        ->whereRaw(sprintf('YEAR(date) = %s', $year))
                        ->count();

        return response()->json(JResponse::set(true, null, $visitas));
    }

    public function registerVisit($member_id, Request $request) {
        $visit = Visit::create($request->all());

        return response()->json(JResponse::set(true, 'Visita registrada exitosamente'));
    }

}
