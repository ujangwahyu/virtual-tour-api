<?php

/*
 * @Author: Ujang Wahyu 
 * @Date: 2018-09-05 13:47:53 
 * @Last Modified by: Ujang Wahyu
 * @Last Modified time: 2018-09-07 15:20:54
 */

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\V1\Controller;
use App\Models\Photo360;
use JD\Cloudder\Facades\Cloudder;

class Photo360Controller extends Controller {

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request){
        $user = $request->auth;
        $listData = Photo360::with('user')->where('user_id', $user->id)->get();

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
    public function show($id, Request $request){
        $user = $request->auth;
        $listData = Photo360::with('user')->where('user_id', $user->id)->findOrFail($id);

        $jsonData = [
            'data' => $listData,
            'message' => 'Data berhasil diambil.'
        ];

        return $this->response($jsonData, 'ok');
    }

    public function byRegion($id, Request $request){ 

        $listData = Photo360::with('user')->where('region_id', $id)->get();

        $jsonData = [
            'data' => $listData,
            'message' => 'Data berhasil diambil.'
        ];

        return $this->response($jsonData, 'ok');
    }

    public function newPhoto360($id, Request $request){ 
    
        $listData = Photo360::with('user')->where('region_id', $id)->get();

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
            'description'                       => 'required',
            'scrolling_enabled'                 => 'required|integer',
            'min_distance_to_enable_scrolling'  => 'required',
            'accelerometer_enabled'             => 'required|integer',
            'interval'                          => 'required',
            'sensitivity'                       => 'required',
            'left_right_enabled'                => 'required',
            'up_down_enabled'                   => 'required'
        ]);

        $photo_url = $request->file('url');
        $cover_url = $request->file('cover_url');

        $url = Cloudder::upload($photo_url->getPathName(), null, array(
            "folder" => "Virtualtour/Photo360",
            "use_filename" => TRUE, 
            "unique_filename" => FALSE
        ));

        $c_url = Cloudder::upload($cover_url->getPathName(), null, array(
            "folder" => "Virtualtour/Covertour",
            "use_filename" => TRUE, 
            "unique_filename" => FALSE
        ));

        $listData = new Photo360;
        $listData->name = $request->name;
        $listData->url = $url->getResult()['url'];
        $listData->cover_url = $c_url->getResult()['url'];
        $listData->description = $request->description;
        $listData->scrolling_enabled = $request->scrolling_enabled;
        $listData->min_distance_to_enable_scrolling = $request->min_distance_to_enable_scrolling;
        $listData->accelerometer_enabled = $request->accelerometer_enabled;
        $listData->interval = $request->interval;
        $listData->sensitivity = $request->sensitivity;
        $listData->left_right_enabled = $request->left_right_enabled;
        $listData->up_down_enabled = $request->up_down_enabled; 
        $listData->region_id = $user->region_id;
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
            'description'                       => 'required',
            'scrolling_enabled'                 => 'required|integer',
            'min_distance_to_enable_scrolling'  => 'required',
            'accelerometer_enabled'             => 'required|integer',
            'interval'                          => 'required',
            'sensitivity'                       => 'required',
            'left_right_enabled'                => 'required',
            'up_down_enabled'                   => 'required'
        ]);

        // upload Image
        if(!empty($request->file('p_url'))){
            $image = $request->file('p_url');

            $d = Cloudder::upload($image->getPathName(), null, array(
                "folder" => "Virtualtour/Photo360",
                "use_filename" => TRUE, 
                "unique_filename" => FALSE
            ));

            $pUrl = $d->getResult()['url'];
        }

        // upload icon
        if(!empty($request->file('cover_url'))){
            $image = $request->file('cover_url');

            $d = Cloudder::upload($image->getPathName(), null, array(
                "folder" => "Virtualtour/Covertour",
                "use_filename" => TRUE, 
                "unique_filename" => FALSE
            ));

            $cUrl = $d->getResult()['url'];
        }
 

        $listData = Photo360::findOrFail($id);
        $listData->name = $request->name; 
        if(!empty($request->file('p_url'))){
            $listData->p_url = $pUrl;
        }
        if(!empty($request->file('cover_url'))){
            $listData->cover_url = $cUrl;
        }
        $listData->description = $request->description;
        $listData->scrolling_enabled = $request->scrolling_enabled;
        $listData->min_distance_to_enable_scrolling = $request->min_distance_to_enable_scrolling;
        $listData->accelerometer_enabled = $request->accelerometer_enabled;
        $listData->interval = $request->interval;
        $listData->sensitivity = $request->sensitivity;
        $listData->left_right_enabled = $request->left_right_enabled;
        $listData->up_down_enabled = $request->up_down_enabled; 
        $listData->region_id = $user->region_id; 
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
        $data = Photo360::findOrFail($id);
        $data->delete();

        $jsonData = [
            'data' => $data,
            'message' => 'Data berhasil dihapus.'
        ];

        return $this->response($jsonData, 'ok');
    }

}