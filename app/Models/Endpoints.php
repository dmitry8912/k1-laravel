<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endpoints extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;

    public function gateway() {
        return $this->hasOne('App\Models\Gateways','id','gatewayId')->with('credential');
    }

    public function credential() {
        return $this->hasOne('App\Models\Credentials','id','credentialId');
    }

    public function locks() {
        return $this->hasMany('App\Models\EndpointLocks','endpointId','id');
    }

    /*public function bookings() {
        return $this->hasOne('App\Models\EndpointBookings');
    }*/
}
