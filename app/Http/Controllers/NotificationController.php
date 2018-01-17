<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\JResponse;

use App\Notification;

use Input;

class NotificationController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $from = Input::get('from', 0);
        $count = Input::get('count', 10);
        $w = Input::get('query', '');
        $order = Input::get('orders', null);
        $query = Notification::all();
        $q = $query->count();

        // if($order){
        //     $ordrs = explode(",",$order);
        //     foreach($ordrs as $o){
        //         $query->orderBy($o, 'desc');
        //     }
        // }

        $member = Notification::take($count)->skip($from)->get()->toArray();

        return response()->json(JResponse::set(true,'[obj]', $member))->header('RowCount',$q);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $notification = Notification::create($request->all());

        return response()->json(JResponse::set(true, 'Notification creada correctamente', compact('notification')));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $notification = Notification::find($id);

        return response()->json(JResponse::set(true, '[obj]', compact('notification')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $notification = Notification::find($id);
        $notification->fill($request->all())->save();

        return response()->json(JResponse::set(true, 'Notification editada correctamente', compact('notification')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        try {
            Notification::destroy($id);
            return response()->json(JResponse::set(true, 'Notification eliminada correctamente'));
        } catch(\Ecxeption $e) {
            return response()->json(JResponse::set(false, 'Hubo un error en el servidor'));
        }
    }

    public function imageStore(Request $request) {
        $file = $request->file('file');

        try {
            $fileName = 'img_' . str_random(6) . '.jpg';
            $destinationPath = public_path();


            $file->move($destinationPath, $fileName);
            $url = 'http://system.campestrecelaya.mx/api/public/' . $fileName;

            return response()->json(JResponse::set(true, 'Imagen subida correctamente', compact('url')));
        } catch(\Exception $e) {
            return response()->json(JResponse::set(false, 'Hubo un error en el servidor'));
        }
    }

    public function imageDelete(Request $request) {
        var_dump($request->all());
    }
}
