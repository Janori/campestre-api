<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model{
    protected $table = 'permissions';

    protected $fillable = ['code', 'description'];

    public $timestamps = false;

    public function roles(){
        return $this->belongsToMany('App\Models\Role');
    }

}
