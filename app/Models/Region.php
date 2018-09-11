<?php
 /*
 * @Author: Ujang Wahyu 
 * @Date: 2018-09-04 11:11:22 
 * @Last Modified by: Ujang Wahyu
 * @Last Modified time: 2018-09-04 11:22:00
 */

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Region extends Model{
    
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'region';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'cover_url'
    ];

    /**
     * Get User
     */
    
    public function user()
    {
        return $this->hasOne('App\Models\User');
    }

    public function tour()
    {
        return $this->hasMany('App\Models\Tour');
    }

    public function photo360()
    {
        return $this->hasMany('App\Models\Photo360');
    }

}