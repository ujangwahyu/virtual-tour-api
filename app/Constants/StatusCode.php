<?php
/*
 * @Author: Ujang Wahyu 
 * @Date: 2018-09-03 15:19:41 
 * @Last Modified by:   Ujang Wahyu 
 * @Last Modified time: 2018-09-03 15:19:41 
 */

 namespace App\Constants;

/*
    Penggunaan Kode Status : https://en.wikipedia.org/wiki/List_of_HTTP_status_codes
*/

class StatusCode {
    const status = [
        'ok' => [
            'status'        => 'Ok', 
            'status_code'   => 200
        ],
        'created' => [
            'status'        => 'Created', 
            'status_code'   => 201
        ],
        'bad_request' => [
            'status'        => 'Bad Request', 
            'status_code'   => 400
        ],
        'unauthorized' => [
            'status'        => 'Unauthorized', 
            'status_code'   => 401
        ],
        'forbiden' => [
            'status'        => 'Forbiden', 
            'status_code'   => 403
        ],
        'not_found' => [
            'status'        => 'Not Found', 
            'status_code'   => 404
        ],
        'mna' => [
            'status'        => 'Method Not Allowed', 
            'status_code'   => 405
        ],
    ];
}
