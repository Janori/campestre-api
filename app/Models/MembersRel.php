<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembersRel extends Model{
    protected $table = 'members_rel';

    protected $fillable = ['id_member', 'id_ref', 'pin', 'rfid', 'fmd', 'code'];

    public $timestamps = false;

    public function member(){
        return $this->hasOne('App\Models\Member', 'id', 'id_member');
    }

}
