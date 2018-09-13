<?php
/*
 * @Author: Ujang Wahyu 
 * @Date: 2018-09-05 10:49:40 
 * @Last Modified by: Ujang Wahyu
 * @Last Modified time: 2018-09-07 15:13:24
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
        $listData = Hotspot::findOrFail($id);

        $jsonData = [
            'data' => $listData,
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

        $listData = new Hotspot;
        $listData->pos_x = $request->pos_x;
        $listData->pos_y = $request->pos_y;
        $listData->pos_z = $request->pos_z;
        $listData->pos_roll = $request->pos_roll;
        $listData->pos_pitch = $request->pos_pitch;
        $listData->pos_yaw = $request->pos_yaw;
        $listData->destination = $request->destination;
        $listData->width = $request->width; 
        $listData->height = $request->height; 
        $listData->scene_id = $request->scene_id;
        $listData->save();

        $jsonData = [
            'data'=> $listData, 
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
 
        $listData = Hotspot::findOrFail($id);
        $listData->pos_x = $request->pos_x;
        $listData->pos_y = $request->pos_y;
        $listData->pos_z = $request->pos_z;
        $listData->pos_roll = $request->pos_roll;
        $listData->pos_pitch = $request->pos_pitch;
        $listData->pos_yaw = $request->pos_yaw;
        $listData->destination = $request->destination;
        $listData->width = $request->width; 
        $listData->height = $request->height; 
        $listData->scene_id = $request->scene_id;
        $listData->save();

        $jsonData = [
            'data' => $listData,
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