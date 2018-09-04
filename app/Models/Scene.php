<?php
/*
 * @Author: Ujang Wahyu 
 * @Date: 2018-09-04 11:45:41 
 * @Last Modified by: Ujang Wahyu
 * @Last Modified time: 2018-09-04 12:32:14
 */
namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Scene extends Model{
    
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'scene';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'url','scrolling_enabled', 'min_distance_to_enable_scrolling','accelerometer_enabled', 'interval','sensitivity', 'left_right_enabled','up_down_enabled'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function tour()
    {
        return $this->belongsTo('App\Models\Tour');
    }

    public function hotspot()
    {
        return $this->hasMany('App\Models\Hotspot');
    }
    
}