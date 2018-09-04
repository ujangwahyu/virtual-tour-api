<?php
/*
 * @Author: Ujang Wahyu 
 * @Date: 2018-09-04 11:48:41 
 * @Last Modified by: Ujang Wahyu
 * @Last Modified time: 2018-09-04 12:35:30
 */

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Setting extends Model{
    
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'setting';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'atv_min', 'atv_max','ath_min', 'ath_max','reverse_rotation', 'rotation_sensitivity','v_look_at', 'h_look_at','zoom_levels','fov_min', 'fov_max','fov_sensitivity','fov','fov_factor', 'zoom_factor','zoom_level'
    ]; 
}