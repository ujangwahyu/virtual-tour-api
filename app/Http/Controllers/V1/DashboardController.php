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
use App\Models\Tour; 

class DashboardController extends Controller {

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request){

        $listUser = User::where('role', 1)->get();
        $totalRegion = Region::count();
        $totalTour = Tour::count();
        $totalScene = Scene::count(); 
        $totalHotspot = Scene::count();  
        $jsonData = [ 
                'user' => $listUser,
                'region' => $totalRegion.' Region',
                'tour' => $totalTour.' Tour', 
                'scene' => $totalScene.' Scene', 
                'Hotspot' => $totalHotspot.' Hotspot', 
                'message' => 'Data berhasil diambil.'
        ];
        // $jsonData = [
        //     'data' => $listData,
        //     'message' => 'Data berhasil diambil.'
        // ];

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
}