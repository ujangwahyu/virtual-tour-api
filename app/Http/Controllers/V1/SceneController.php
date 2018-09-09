<?php

/*
 * @Author: Ujang Wahyu 
 * @Date: 2018-09-05 09:05:33 
 * @Last Modified by: Ujang Wahyu
 * @Last Modified time: 2018-09-05 15:29:44
 */

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\V1\Controller;
use App\Models\Scene;
use JD\Cloudder\Facades\Cloudder;

class SceneController extends Controller {

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request){
        $listData = Scene::with('hotspot')->get();

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
        $dt = Scene::with('hotspot')->findOrFail($id);

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
            'name'                              => 'required',
            'url'                               => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'scrolling_enabled'                 => 'required|integer',
            'min_distance_to_enable_scrolling'  => 'required',
            'accelerometer_enabled'             => 'required|integer',
            'interval'                          => 'required',
            'sensitivity'                       => 'required',
            'left_right_enabled'                => 'required',
            'up_down_enabled'                   => 'required',
            'tour_id'                           => 'required'
        ]);

        $coverUrl = $request->file('url'); 

        $cUrl = Cloudder::upload($coverUrl->getPathName(), null, array(
            "folder" => "Virtualtour/Photo360",
            "use_filename" => TRUE, 
            "unique_filename" => FALSE
        ));

        $dt = new Scene;
        $dt->name = $request->name;
        $dt->url = $cUrl->getResult()['url'];
        $dt->scrolling_enabled = $request->scrolling_enabled;
        $dt->min_distance_to_enable_scrolling = $request->min_distance_to_enable_scrolling;
        $dt->accelerometer_enabled = $request->accelerometer_enabled;
        $dt->interval = $request->interval;
        $dt->sensitivity = $request->sensitivity;
        $dt->left_right_enabled = $request->left_right_enabled;
        $dt->up_down_enabled = $request->up_down_enabled; 
        $dt->tour_id = $request->tour_id; 
        $dt->user_id = $user->id;
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
        $user = $request->auth;
        $this->validate($request, [
            'name'                              => 'required', 
            'scrolling_enabled'                 => 'required|integer',
            'min_distance_to_enable_scrolling'  => 'required',
            'accelerometer_enabled'             => 'required|integer',
            'interval'                          => 'required',
            'sensitivity'                       => 'required',
            'left_right_enabled'                => 'required',
            'up_down_enabled'                   => 'required',
            'tour_id'                           => 'required'
        ]);

        // upload Image
        if(!empty($request->file('url'))){
            $image = $request->file('url');

            $d = Cloudder::upload($image->getPathName(), null, array(
                "folder" => "Virtualtour/Photo360",
                "use_filename" => TRUE, 
                "unique_filename" => FALSE
            ));

            $cUrl = $d->getResult()['url'];
        }
 

        $dt = Scene::findOrFail($id);
        $dt->name = $request->name; 
        if(!empty($request->file('url'))){
            $dt->url = $cUrl;
        }
        $dt->scrolling_enabled = $request->scrolling_enabled;
        $dt->min_distance_to_enable_scrolling = $request->min_distance_to_enable_scrolling;
        $dt->accelerometer_enabled = $request->accelerometer_enabled;
        $dt->interval = $request->interval;
        $dt->sensitivity = $request->sensitivity;
        $dt->left_right_enabled = $request->left_right_enabled;
        $dt->up_down_enabled = $request->up_down_enabled; 
        $dt->tour_id = $request->tour_id; 
        $dt->user_id = $user->id;
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
        $data = Scene::findOrFail($id);
        $data->delete();

        $jsonData = [
            'data' => $data,
            'message' => 'Data berhasil dihapus.'
        ];

        return $this->response($jsonData, 'ok');
    }

}