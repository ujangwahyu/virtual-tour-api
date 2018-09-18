<?php

/*
 * @Author: Ujang Wahyu 
 * @Date: 2018-09-03 15:37:52 
 * @Last Modified by: Ujang Wahyu
 * @Last Modified time: 2018-09-05 16:54:20
 */

namespace App\Http\Controllers\V1;

use Validator;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\V1\Controller; 

use App\Models\User;

class RegisterController extends Controller {

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function register(Request $request)
    { 
        try {
            $id = $request->input('nik');
            $email = $request->input('email');
            $password = $request->input('password');
            $name = $request->input('name'); 
            $photo_url = $request->input('photo_url');  
            $role = $request->input('role'); 
            $token = $request->input('token'); 
            $region_id = $request->input('region_id'); 

            $jsonData = new User;
            $jsonData->id = $id;
            $jsonData->email = $email;
            $jsonData->password = app('hash')->make($password);  
            $jsonData->name = $name;
            $jsonData->photo_url = $photo_url;  
            $jsonData->role = $role; 
            $jsonData->token = sha1($name);
            $jsonData->region_id = $region_id; 

            if($jsonData->save()){
                
                $res['message'] = "Success!";
                $res['value'] = 1;
                $res['data'] = $jsonData;
                return response($res);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            $res['status'] = false;
            $res['message'] = $ex->getMessage();
            return response($res, 500);
        }
    }

}