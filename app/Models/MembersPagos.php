<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembersPagos extends Model {
    protected $table = 'members_pagos';
    public $timestamps = false;

    protected $fillable = ['member_id', 'paid_up', 'payment_date', 'paid_amount'];
}
