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
 
}