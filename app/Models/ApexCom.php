<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApexCom extends Model
{
    protected $fillable = [
      'id',
      'name',
      'avatar',
      'banner',
      'rules',
      'description',
    ];

    public $incrementing = false;

    /**
     * Get the posts for the apexCom.
     */
    public function posts()
    {
        return $this->hasMany(post::class, 'apex_id', 'id');
    }

    public function subscribers()
    {
        return $this->hasMany(Subscriber::class, 'apexID');
    }

    public function isSubscribedBy($userID)
    {
        return $this->subscribers()->where(compact('userID'))->exists();
    }
}
