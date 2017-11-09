<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Helpers\JResponse;
use App\Models\Member;

class ReportsController extends Controller {
    public function activeMembers() {
        $query = DB::table('members')
                    ->join('members_data_fixed', 'members.id', '=', 'members_data_fixed.id_member')
                    ->join('members_rel', 'members.id', '=', 'members_rel.id_member')
                    ->select('members_rel.code', 'nombre', 'status')
                    ->whereIn('tipo', ['T'])
                    ->where('status', 'ACTIVO')
                    ->get();

        return response()->json(JResponse::set(true, null, $query));
    }

    public function paymentPendingMembers() {
        $query = DB::table('members')
                    ->join('members_data_fixed', 'members.id', '=', 'members_data_fixed.id_member')
                    ->join('members_rel', 'members.id', '=', 'members_rel.id_member')
                    ->select('members_rel.code', 'nombre', 'status')
                    ->whereIn('tipo', ['T'])
                    ->where('status', 'PENDIENTE DE PAGO')
                    ->get();

        return response()->json(JResponse::set(true, null, $query));
    }

    public function debtorsMembers() {
        $query = DB::table('members')
                    ->join('members_data_fixed', 'members.id', '=', 'members_data_fixed.id_member')
                    ->join('members_rel', 'members.id', '=', 'members_rel.id_member')
                    ->select('members_rel.code', 'nombre', 'status')
                    ->whereIn('tipo', ['T'])
                    ->where('status', 'DEUDOR')
                    ->get();

        return response()->json(JResponse::set(true, null, $query));
    }

    public function downMembers() {
        $query = DB::table('members')
                    ->join('members_data_fixed', 'members.id', '=', 'members_data_fixed.id_member')
                    ->join('members_rel', 'members.id', '=', 'members_rel.id_member')
                    ->select('members_rel.code', 'nombre', 'status')
                    ->whereIn('tipo', ['T', 'A'])
                    ->where('status', 'BAJA')
                    ->get();

        return response()->json(JResponse::set(true, null, $query));
    }
}
