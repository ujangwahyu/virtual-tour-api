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
use App\Models\User;
use App\Models\Scene;
use App\Models\Hotspot;
use App\Models\Photo360;
use App\Models\Video360;
class DashboardController extends Controller {

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request){

        $region = Region::with(['user'])->get();

        $listRegion = [];

        foreach($region as $reg){
            $scene = 0;
            $tour = 0;
            $hotspot = 0;
            $photo360 = 0;
            $video360 = 0;
            foreach($reg->user as $user){
                $scene += Scene::where('user_id', $user->id)->count();
                $tour += Tour::where('user_id', $user->id)->count();
                $hotspot += Hotspot::where('user_id', $user->id)->count();
                $photo360 += Photo360::where('user_id', $user->id)->count();
                $video360 += Video360::where('user_id', $user->id)->count();
            }

            $listRegion[] = [
                'region' => $reg->name,
                'tour' => $tour,
                'scene' => $scene,
                'hotspot' => $hotspot,
                'photo360' => $photo360,
                'video360' => $video360
            ];
        }

        $jsonData = [ 
            'data' => $listRegion,
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
}