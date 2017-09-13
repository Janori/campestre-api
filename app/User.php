<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Http\Controllers\AuthenticateController;
use App\Http\Middleware\VerifyJWTToken;

use App\Models\Member;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /*public static $kind_options = [ 
        ['key'=>'u', 'value' => 'Usuario'],
        ['key'=>'p', 'value' => 'Proveedor'],
        ['key'=>'x', 'value' => 'Sin definir']
    ];*/

    protected $table = 'users';

    protected $fillable = ['email', 'username', 'password', 'role_id', 'member_id'];

    protected $hidden = ['password', 'remember_token'];

    protected $dates = ['created_at', 'updated_at'];

    protected $appends = ['role_description', 'member_val'];

    public function getRoleDescriptionAttribute(){
        if($this->role_id == null || $this->role == null){
            return 'Sin rol';
        }else{
            return $this->role->description;    
        }
    }

    public function getMemberValAttribute(){
        return $this->member;
        if($this->member_id != null || $this->member != null){
            //return $this->member;
        }
        return null;
    }

    public function role(){
        try{
            return $this->belongsTo('App\Models\Role');   
        }catch(\Exception $e){
            return null;
        }
    }
    public function member(){
        return $this->belongsTo('App\Models\Member');
    }

    public function setPasswordAttribute($value) {
        if(!empty($value))
            $this->attributes['password'] = bcrypt($value);
    }

    public function setEmailAttibute($value){
        if(!empty($value) && (is_null($this->attributes['username']) || $this->attributes['username'] == "")){
            $this->attributes['username'] = $value;
        }
    }

    public function getDates(){
        return array();
    }

    public static function HasPermission($permission){
        $user = AuthenticateController::getUserFromToken(VerifyJWTToken::$token);
        if(!$user->role) return false;
        foreach($user->role->permissions as $perm){
            if($perm['code'] == $permission){
                return true;
            }
        }
        return false;   
    }

}



















