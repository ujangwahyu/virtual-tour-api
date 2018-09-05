<?php
/*
 * @Author: Ujang Wahyu 
 * @Date: 2018-09-05 15:19:13 
 * @Last Modified by: Ujang Wahyu
 * @Last Modified time: 2018-09-05 15:23:24
 */

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\V1\Controller;
use App\Models\Region;
use JD\Cloudder\Facades\Cloudder;

class RegionController extends Controller {

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request){
        $listData = Region::with('tour.scene.hotspot')->get();

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
        $dt = Region::with('tour.scene.hotspot')->findOrFail($id);

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
            'name'              => 'required',
            'cover_url'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description'       => 'required|string',
            'region_id'         => 'required'
        ]);

        $coverUrl = $request->file('cover_url'); 

        $cUrl = Cloudder::upload($coverUrl->getPathName(), null, array(
            "folder" => "Virtualtour/Covertour",
            "use_filename" => TRUE, 
            "unique_filename" => FALSE
        ));

        $dt = new Tour;
        $dt->name = $request->name;
        $dt->cover_url = $cUrl->getResult()['url'];
        $dt->description = $request->description;
        $dt->region_id = $request->region_id;
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
            'name'      => 'required', 
            'description'      => 'required|string',
            'region_id'      => 'required' 
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
 

        $dt = Tour::findOrFail($id);
        $dt->name = $request->name; 
        if(!empty($request->file('cover_url'))){
            $dt->cover_url = $cUrl;
        }
        $dt->description = $request->description;
        $dt->region_id = $request->region_id; 
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
        $data = Tour::findOrFail($id);
        $data->delete();

        $jsonData = [
            'data' => $data,
            'message' => 'Data berhasil dihapus.'
        ];

        return $this->response($jsonData, 'ok');
    }

}