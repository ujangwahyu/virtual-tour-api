<?php
/*
 * @Author: Ujang Wahyu 
 * @Date: 2018-09-05 10:49:40 
 * @Last Modified by: Ujang Wahyu
 * @Last Modified time: 2018-09-05 11:28:41
 */

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\V1\Controller;
use App\Models\Hotspot;
use JD\Cloudder\Facades\Cloudder;

class HotspotController extends Controller {

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request){
        $listData = Hotspot::get();

        $jsonData = [
            'data' => $listData,
            'message' => 'Data berhasil diambil.'
        ];

        return $this->response($jsonData, 'ok');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $dt = Hotspot::findOrFail($id);

        $jsonData = [
            'data' => $dt,
            'message' => 'Data berhasil diambil.'
        ];

        return $this->response($jsonData, 'ok');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $user = $request->auth;
        $this->validate($request, [ 
            'pos_x'          => 'required',
            'pos_y'          => 'required',
            'pos_z'          => 'required',
            'pos_roll'       => 'required',
            'pos_pitch'      => 'required',
            'pos_yaw'        => 'required',
            'destination'    => 'required|integer',
            'width'          => 'required',
            'height'         => 'required',
            'scene_id'       => 'required|integer'
        ]);

        $dt = new Hotspot;
        $dt->pos_x = $request->pos_x;
        $dt->pos_y = $request->pos_y;
        $dt->pos_z = $request->pos_z;
        $dt->pos_roll = $request->pos_roll;
        $dt->pos_pitch = $request->pos_pitch;
        $dt->pos_yaw = $request->pos_yaw;
        $dt->destination = $request->destination;
        $dt->width = $request->width; 
        $dt->height = $request->height; 
        $dt->scene_id = $request->scene_id;
        $dt->save();

        $jsonData = [
            'data'=> $dt, 
            'message'=> 'Data berhasil dibuat.'
        ];
        return $this->response($jsonData, 'created');
    }

    /**
     * Update the specified resource.
     *
     * @param  int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request){
        $this->validate($request, [
            'pos_x'          => 'required',
            'pos_y'          => 'required',
            'pos_z'          => 'required',
            'pos_roll'       => 'required',
            'pos_pitch'      => 'required',
            'pos_yaw'        => 'required',
            'destination'    => 'required|integer',
            'width'          => 'required',
            'height'         => 'required',
            'scene_id'       => 'required|integer'
        ]);
 
        $dt = Hotspot::findOrFail($id);
        $dt->pos_x = $request->pos_x;
        $dt->pos_y = $request->pos_y;
        $dt->pos_z = $request->pos_z;
        $dt->pos_roll = $request->pos_roll;
        $dt->pos_pitch = $request->pos_pitch;
        $dt->pos_yaw = $request->pos_yaw;
        $dt->destination = $request->destination;
        $dt->width = $request->width; 
        $dt->height = $request->height; 
        $dt->scene_id = $request->scene_id;
        $dt->save();

        $jsonData = [
            'data' => $dt,
            'message' => 'Data berhasil diupdate.'
        ];

        return $this->response($jsonData, 'ok');
    }

    /**
     * Delete the specified resource.
     *
     * @param  int $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request){
        $data = Hotspot::findOrFail($id);
        $data->delete();

        $jsonData = [
            'data' => $data,
            'message' => 'Data berhasil dihapus.'
        ];

        return $this->response($jsonData, 'ok');
    }

}