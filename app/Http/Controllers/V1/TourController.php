<?php

/*
 * @Author: Ujang Wahyu 
 * @Date: 2018-09-04 14:15:07 
 * @Last Modified by: Ujang Wahyu
 * @Last Modified time: 2018-09-05 15:29:24
 */

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\V1\Controller;
use App\Models\Tour;
use JD\Cloudder\Facades\Cloudder;

class TourController extends Controller {

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request){
        $user = $request->auth;
        $listData = Tour::with('scene.hotspot','user')->where('user_id', $user->id)->get();

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
        $listData = Tour::with('scene.hotspot','user')->where('user_id', $user->id)->findOrFail($id);

        $jsonData = [
            'data' => $listData,
            'message' => 'Data berhasil diambil.'
        ];

        return $this->response($jsonData, 'ok');
    }

    public function byRegion($id, Request $request){ 
    
        $listData = Tour::with('scene.hotspot','user')->where('region_id', $id)->get();

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
            'name'              => 'required',
            'cover_url'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description'       => 'required|string',
            'location'       => 'required|string' 
        ]);

        $coverUrl = $request->file('cover_url'); 

        $cUrl = Cloudder::upload($coverUrl->getPathName(), null, array(
            "folder" => "Virtualtour/Covertour",
            "use_filename" => TRUE, 
            "unique_filename" => FALSE
        ));

        $listData = new Tour;
        $listData->name = $request->name;
        $listData->cover_url = $cUrl->getResult()['url'];
        $listData->description = $request->description;
        $listData->location = $request->location;
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
            'name'      => 'required', 
            'description'      => 'required|string' 
        ]);

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
 

        $listData = Tour::findOrFail($id);
        $listData->name = $request->name; 
        if(!empty($request->file('cover_url'))){
            $listData->cover_url = $cUrl;
        }
        $listData->description = $request->description;
        $listData->location = $request->location;
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
        $data = Tour::findOrFail($id);
        $data->delete();

        $jsonData = [
            'data' => $data,
            'message' => 'Data berhasil dihapus.'
        ];

        return $this->response($jsonData, 'ok');
    }

}