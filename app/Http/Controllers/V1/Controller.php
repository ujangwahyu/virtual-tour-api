<?php

/*
 * @Author: Ujang Wahyu 
 * @Date: 2018-09-03 15:37:52 
 * @Last Modified by: Ujang Wahyu
 * @Last Modified time: 2018-09-05 09:06:16
 */

namespace App\Http\Controllers\V1;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Constants\StatusCode;

class Controller extends BaseController{

    public function response($data=array(), $status="ok"){
        $data['status'] = StatusCode::status[$status]['status'];
        return response()->json($data, StatusCode::status[$status]['status_code'], [], JSON_PRETTY_PRINT);
    }

}
