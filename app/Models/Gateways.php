<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gateways extends Model
{
    use HasFactory;
    protected $keyType = 'string';
    public $incrementing = false;

    public function credential() {
        return $this->hasOne('App\Models\Credentials','id','credentialId');
    }
}
