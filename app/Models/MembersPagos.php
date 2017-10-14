<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembersPagos extends Model {
    protected $table = 'members_pagos';
    public $timestamps = false;

    protected $fillable = ['member_id', 'month', 'year', 'payment_date'];

    public function setMonthAttribute($value) {
            $months = [
                'Enero'         => 1,
                'Febrero'       => 2,
                'Marzo'         => 3,
                'Abril'         => 4,
                'Mayo'          => 5,
                'Junio'         => 6,
                'Julio'         => 7,
                'Agosto'        => 8,
                'Septiembre'    => 9,
                'Octubre'       => 10,
                'Noviembre'     => 11,
                'Diciembre'     => 12
            ];

            $this->attributes['month'] = $months[$value];
    }
}
