<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\MembersRel;

class Member extends Model{
    protected $table = 'members';

    protected $fillable = ['nombre', 'tipo'];

    protected $appends = ['info', 'data'];

    protected $hidden = ['members_rel', 'members_data'];

    public $timestamps = false;

    public function getInfoAttribute(){
    	if($this->members_rel != null){
    		return $this->members_rel;
    	}return null;
    }
    public function getDataAttribute(){ 
        return $this->members_data;
        if($this->members_data != null){
            return $this->members_data;
        }return null;
    }

    public function user(){
        return $this->hasOne('App\User');
    }

    public function members_rel(){
        return $this->belongsTo('App\Models\MembersRel', 'id', 'id_member');
    }
    public function members_data(){
        return $this->belongsTo('App\Models\MembersData', 'id', 'id_member');
    }

}
