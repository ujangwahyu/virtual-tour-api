<?php

/*
 * @Author: Ujang Wahyu 
 * @Date: 2018-09-05 15:43:18 
 * @Last Modified by: Ujang Wahyu
 * @Last Modified time: 2018-09-05 15:52:10
 */

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\V1\Controller;
use App\Models\Video360;
use JD\Cloudder\Facades\Cloudder;

class Video360Controller extends Controller {

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request){
        $user = $request->auth;
        $listData = Video360::with('user')->where('user_id', $user->id)->get();

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
        $listData = Video360::with('user')->where('user_id', $user->id)->findOrFail($id);

        $jsonData = [
            'data' => $listData,
            'message' => 'Data berhasil diambil.'
        ];

        return $this->response($jsonData, 'ok');
    }

    public function byRegion($id, Request $request){ 
    
        $listData = Video360::with('user')->where('region_id', $id)->get();

        $jsonData = [
            'data' => $listData,
            'message' => 'Data berhasil diambil.'
        ];

        return $this->response($jsonData, 'ok');
    }

    public function newVideo360($id, Request $request){ 
    
        $listData = Video360::with('user')->where('region_id', $id)->get();

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

        $coverUrl = $request->file('cover_url'); 
        $c_url = Cloudder::upload($coverUrl->getPathName(), null, array(
            "folder" => "Virtualtour/Covertour",
            "use_filename" => TRUE, 
            "unique_filename" => FALSE
        ));
        $res_curl = $c_url->getResult()['secure_url'];

        $url = $request->file('url'); 
        $v_url = Cloudder::upload_large($url->getPathName(), null, array(
            "folder" => "Virtualtour/Covertour", 
            "use_filename" => TRUE, 
            "unique_filename" => FALSE,
            "resource_type" => "video"
        ));
        $res_vurl = $v_url->getResult()['secure_url'];
    

        $listData = new Video360;
        $listData->name = $request->name;
        $listData->url = $request->res_vurl;
        $listData->cover_url = $res_curl;
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
        if(!empty($request->file('cover_url'))){
            $image = $request->file('cover_url');

            $d = Cloudder::upload($image->getPathName(), null, array(
                "folder" => "Virtualtour/Photo360",
                "use_filename" => TRUE, 
                "unique_filename" => FALSE
            ));

            $cUrl = $d->getResult()['secure_url'];
        }
 

        $listData = Video360::findOrFail($id);
        $listData->name = $request->name; 
        if(!empty($request->file('cover_url'))){
            $listData->url = $cUrl;
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
        $data = Video360::findOrFail($id);
        $data->delete();

        $jsonData = [
            'data' => $data,
            'message' => 'Data berhasil dihapus.'
        ];

        return $this->response($jsonData, 'ok');
    }

}