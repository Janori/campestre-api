<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {
    protected $table = 'notifications';
    protected $fillable = ['title', 'description', 'url', 'created_at'];

    protected $dates = ['created_at', 'updated_at'];

    public function getDates() { return []; }
}
