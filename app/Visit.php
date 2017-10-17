<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model {
    protected $table = 'members_visitas';
    protected $fillable = ['guest_id', 'member_id', 'date', 'days'];
    public $timestamps = false;
}
