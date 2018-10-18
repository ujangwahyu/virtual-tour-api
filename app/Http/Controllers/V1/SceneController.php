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
        $user = $request->auth;
        $listData = Scene::with('hotspot')->where('user_id', $user->id)->get();

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
    public function show(Request $request, $id){
        $user = $request->auth;
        $listData = Scene::with('hotspot')->where('user_id', $user->id)->findOrFail($id);

        $jsonData = [
            'data' => $listData,
            'message' => 'Data berhasil diambil.'
        ];

        return $this->response($jsonData, 'ok');
    }

    public function byTour($id, Request $request){ 
    
        $listData = Scene::with('hotspot','tour')->where('tour_id', $id)->get();

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
        $user = $request->auth;
        $this->validate($request, [
            'name'                              => 'required',
            'url'                               => 'required',
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

        $listData = new Scene;
        $listData->name = $request->name;
        $listData->url = $cUrl->getResult()['url'];
        $listData->scrolling_enabled = $request->scrolling_enabled;
        $listData->min_distance_to_enable_scrolling = $request->min_distance_to_enable_scrolling;
        $listData->accelerometer_enabled = $request->accelerometer_enabled;
        $listData->interval = $request->interval;
        $listData->sensitivity = $request->sensitivity;
        $listData->left_right_enabled = $request->left_right_enabled;
        $listData->up_down_enabled = $request->up_down_enabled; 
        $listData->tour_id = $request->tour_id; 
        $listData->user_id = $user->id;
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
 

        $listData = Scene::findOrFail($id);
        $listData->name = $request->name; 
        if(!empty($request->file('url'))){
            $listData->url = $cUrl;
        }
        $listData->scrolling_enabled = $request->scrolling_enabled;
        $listData->min_distance_to_enable_scrolling = $request->min_distance_to_enable_scrolling;
        $listData->accelerometer_enabled = $request->accelerometer_enabled;
        $listData->interval = $request->interval;
        $listData->sensitivity = $request->sensitivity;
        $listData->left_right_enabled = $request->left_right_enabled;
        $listData->up_down_enabled = $request->up_down_enabled; 
        $listData->tour_id = $request->tour_id; 
        $listData->user_id = $user->id;
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
        $data = Scene::findOrFail($id);
        $data->delete();

        $jsonData = [
            'data' => $data,
            'message' => 'Data berhasil dihapus.'
        ];

        return $this->response($jsonData, 'ok');
    }

}