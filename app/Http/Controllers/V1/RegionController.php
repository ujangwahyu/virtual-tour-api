<?php
/*
 * @Author: Ujang Wahyu 
 * @Date: 2018-09-05 15:19:13 
 * @Last Modified by: Ujang Wahyu
 * @Last Modified time: 2018-09-07 15:37:16
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
        $listData = Region::get();

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
        $listData = Region::findOrFail($id);

        $jsonData = [
            'data' => $listData,
            'message' => 'Data berhasil diambil.'
        ];

        return $this->response($jsonData, 'ok');
    }

    public function indexClient(Request $request){
        $listData = Region::get();

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
    public function showClient($id, Request $request){
        $listData = Region::findOrFail($id);

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
            'name'              => 'required',
            'cover_url'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon'              => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $coverUrl = $request->file('cover_url'); 
        $iconUrl = $request->file('icon'); 

        $cUrl = Cloudder::upload($coverUrl->getPathName(), null, array(
            "folder" => "Virtualtour/Covertour",
            "use_filename" => TRUE, 
            "unique_filename" => FALSE
        ));

        $iUrl = Cloudder::upload($iconUrl->getPathName(), null, array(
            "folder" => "Virtualtour/Covertour",
            "use_filename" => TRUE, 
            "unique_filename" => FALSE
        )); 

        $listData = new Region;
        $listData->name = $request->name;
        $listData->cover_url = $cUrl->getResult()['secure_url'];
        $listData->icon = $iUrl->getResult()['secure_url'];   
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
            'name'              => 'required' 
        ]);

        // upload icon
        if(!empty($request->file('cover_url'))){
            $image = $request->file('cover_url');

            $d = Cloudder::upload($image->getPathName(), null, array(
                "folder" => "Virtualtour/Covertour",
                "use_filename" => TRUE, 
                "unique_filename" => FALSE
            ));

            $cUrl = $d->getResult()['secure_url'];
        }

        if(!empty($request->file('icon'))){
            $image = $request->file('icon');

            $d = Cloudder::upload($image->getPathName(), null, array(
                "folder" => "Virtualtour/Covertour",
                "use_filename" => TRUE, 
                "unique_filename" => FALSE
            ));

            $iUrl = $d->getResult()['secure_url'];
        }
 
        $listData = Region::findOrFail($id);
        $listData->name = $request->name; 
        if(!empty($request->file('cover_url'))){
            $listData->cover_url = $cUrl;
        }
        if(!empty($request->file('icon'))){
            $listData->icon = $iUrl;
        }  

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
        $data = Region::findOrFail($id);
        $data->delete();

        $jsonData = [
            'data' => $data,
            'message' => 'Data berhasil dihapus.'
        ];

        return $this->response($jsonData, 'ok');
    }

}