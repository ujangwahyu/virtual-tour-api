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
use JD\Cloudder\Facades\Cloudder;
use App\Models\User;

class AuthController extends Controller {

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

    /**
     * Create a new token.
     * 
     * @param  \App\User   $user
     * @return string
     */
    protected function jwt(User $user) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + 60*60*24 // Expiration time
        ];
        
        // As you can see we are passing `JWT_SECRET` as the second parameter that will 
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    } 

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     * 
     * @param  \App\User   $user 
     * @return mixed
     */
    public function loginEmail(User $user) {
        $this->validate($this->request, [
            'email'     => 'required|email',
            'password'  => 'required'
        ]);
        // Find the user by email
        $user = User::where('email', $this->request->input('email'))->first();

        if (!$user) {
            // You wil probably have some sort of helpers or whatever
            // to make sure that you have the same response format for
            // differents kind of responses. But let's return the 
            // below respose for now.
            $data = [
                'message' => 'Email does not exist.'
            ];

            return $this->response($data, 'bad_request');
        }
        // Verify the password and generate the token
        if (Hash::check($this->request->input('password'), $user->password)) {
            $data = [
                'profile' => $user,
                'token' => $this->jwt($user)
            ];

            return $this->response($data, 'ok');
        }
        // Bad Request response
        $data = [
            'message' => 'Email or password is wrong.'
        ];

        return $this->response($data, 'bad_request');
    }

    public function register(Request $request)
    { 
        try {
            $id = $request->input('nik');
            $email = $request->input('email');
            $password = $request->input('password');
            $name = $request->input('name');  
            $photo_url = $request->file('photo_url'); 
            $role = $request->input('role'); 
            $token = $request->input('token'); 
            $region_id = $request->input('region_id'); 

            $pUrl = Cloudder::upload($photo_url->getPathName(), null, array(
                "folder" => "Virtualtour/Covertour",
                "use_filename" => TRUE, 
                "unique_filename" => FALSE
            ));

            $jsonData = new User;
            $jsonData->id = $id;
            $jsonData->email = $email;
            $jsonData->password = app('hash')->make($password);  
            $jsonData->name = $name;
            $jsonData->photo_url = $pUrl->getResult()['url'];  
            $listData->cover_url = 
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