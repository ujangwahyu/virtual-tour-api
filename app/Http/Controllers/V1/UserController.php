<?php

/*
 * @Author: Ujang Wahyu 
 * @Date: 2018-09-03 15:37:52 
 * @Last Modified by: Ujang Wahyu
 * @Last Modified time: 2018-09-04 15:44:37
 */

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\V1\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use JD\Cloudder\Facades\Cloudder;
class UserController extends Controller {

    public function __construct(){
        // $this->middleware('auth');
    }

    /**
    * Me.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function me(Request $request){
        $user = $request->auth;

        $jsonData = [
            'profile'      => $user
        ];

        return $this->response($jsonData, 'ok');
    }

    public function index(Request $request){
        $user = $request->auth;
        $listData = User::where('role', 1)->get();

        $jsonData = [
            'data' => $listData,
            'message' => 'Data berhasil diambil.'
        ];

        return $this->response($jsonData, 'ok');
    }

    public function show($id, Request $request){ 
        $listData = User::findOrFail($id);

        $jsonData = [
            'data' => $listData,
            'message' => 'Data berhasil diambil.'
        ];

        return $this->response($jsonData, 'ok');
    }

    public function register(Request $request){
        $this->validate($request, [
            'id'                              => 'required',
            'email'                           => 'required',
            'password'                        => 'required',
            'name'                            => 'required',
            'photo_url'                       => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'role'                            => 'required',
            'region_id'                       => 'required'
        ]);
 
        $photoUrl = $request->file('photo_url');

        $pUrl = Cloudder::upload($photoUrl->getPathName(), null, array(
            "folder" => "Virtualtour/Photo360",
            "use_filename" => TRUE, 
            "unique_filename" => FALSE
        ));
 

        $listData = new User;
        $listData->id = $request->id;
        $listData->name = $request->name;
        $listData->email = $request->email;
        $listData->password = $request->password; 
        $listData->photo_url = $pUrl->getResult()['url'];  
        $listData->role = $request->role; 
        $listData->region_id = $request->region_id; 
        $listData->save();

        $jsonData = [
            'data'=> $listData, 
            'message'=> 'Data berhasil dibuat.'
        ];
        return $this->response($jsonData, 'created');
    }

    public function update($id, Request $request){ 
        $this->validate($request, [
            'id'                              => 'required',
            'email'                           => 'required',
            'password'                        => 'required',
            'name'                            => 'required',
            'role'                            => 'required',
            'region_id'                       => 'required'
        ]);

        // upload icon
        if(!empty($request->file('photo_url'))){
            $image = $request->file('photo_url');

            $d = Cloudder::upload($image->getPathName(), null, array(
                "folder" => "Virtualtour/Covertour",
                "use_filename" => TRUE, 
                "unique_filename" => FALSE
            ));

            $pUrl = $d->getResult()['url'];
        }
 

        $listData = User::findOrFail($id);
        $listData->id = $request->id;
        $listData->email = $request->email;
        $listData->password = $request->password; 
        $listData->name = $request->name; 

        if(!empty($request->file('photo_url'))){
            $listData->photo_url = $cUrl;
        }   
        $listData->role = $request->role; 
        $listData->region_id = $request->region_id; 
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
        $data = User::findOrFail($id);
        $data->delete();

        $jsonData = [
            'data' => $data,
            'message' => 'Data berhasil dihapus.'
        ];

        return $this->response($jsonData, 'ok');
    }
}