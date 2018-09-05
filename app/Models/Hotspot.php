<?php
 /*
 * @Author: Ujang Wahyu 
 * @Date: 2018-09-04 11:35:04 
 * @Last Modified by: Ujang Wahyu
 * @Last Modified time: 2018-09-05 10:58:21
 */

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Hotspot extends Model{
    
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hotspot';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pos_x', 'pos_y','pos_z', 'pos_roll','pos_pitch', 'pos_yaw','destination', 'width','height'
    ];

    public function scene()
    {
        return $this->belongsTo('App\Models\Scene');
    }
    
}