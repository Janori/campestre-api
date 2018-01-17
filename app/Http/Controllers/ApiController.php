<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Notification;
use App\Helpers\JResponse;

class ApiController extends Controller {
    public function login() {
        // TODO
    }

    public function newsfeed() {
        $notifications = Notification::all();

        return response()->json(JResponse::set(true, null, $notifications));
    }

    public function pools() {
        $pools = [
            'first_pool'    => 33.4,
            'second_pool'   => 35.5,
        ];

        return response()->json(JResponse::set(true, null, $pools));
    }

    public function access() {
        $access  = DB::table('access_history')
                    ->take(10)
                    ->get();


        return response()->json(JResponse::set(true, null, $access));
    }

    public function changePassword(Request $request) {
        // TODO

        return response()->json(JResponse::set(true, 'Contraseña cambiada éxitosamente'));
    }
}
