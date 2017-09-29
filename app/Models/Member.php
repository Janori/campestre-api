<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\MembersRel;
use App\Models\Member;

class Member extends Model{
    protected $table = 'members';

    protected $fillable = ['nombre', 'tipo'];

    protected $appends = ['info', 'data', 'assoc', 'father'];

    protected $hidden = ['members_rel', 'members_data'];

    public $timestamps = false;

    public function getFatherAttribute() {
        if($this->members_rel != null) {
            if($this->tipo == 'A') {
                $rel = MembersRel::where('id_member', $this->id)->where('id_ref', '<>', $this->id)->first();

                if(!is_null($rel))
                    return $rel->id_ref;
                else
                    return null;
            }
        }

        return null;
    }

    public function getAssocAttribute(){
        if($this->members_rel != null){
            $mr = MembersRel::where('id_ref', $this->id)->where('id_member','<>',$this->id)->lists('id_member')->toArray();
            //return $mr;
            return Member::whereIn('id', $mr)->get();
        }return null;
    }
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

    public function members_historial() {
        return $this->hasMany('App\Models\MembersHistorial', 'member_id', 'id');
    }

}
