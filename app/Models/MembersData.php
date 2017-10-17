<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\MembersRel;

class MembersData extends Model{
    protected $table = 'members_data_fixed';

    protected $fillable = ['id_member', 'code', 'tipo_membresia', 'direccion', 'email',
                           'rfc', 'fecha_nacimiento', 'tipo_sangre', 'celular',
                           'telefono', 'status'];

    //protected $appends = ['relations'];

    public $timestamps = false;
    protected $primaryKey = 'Id';


    /*public function getRelationsAttribute(){
    	if($this->members_rel != null){
    		return $this->members_rel;
    	}return null;
    }*/

    public function member(){
        return $this->hasOne('App\Models\Members');
    }

}
