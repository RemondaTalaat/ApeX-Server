<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class apexCom extends Model
{
    protected $fillable = [
      'id',
      'avatar',
      'banner',
      'rules',
      'description',
    ];
    public $table = 'apexcoms';
    public $incrementing = false;
}
