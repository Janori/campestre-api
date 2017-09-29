<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembersHistorial extends Model {
    protected $table = 'members_historial';
    public $timestamps = false;

    protected $fillable = ['member_id', 'month', 'date'];
}
