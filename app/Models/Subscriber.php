<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = [
      'apexID',
      'userID',
    ];
    public $incrementing = false;

    /**
     * A relation to the subscribed user
     * 
     * @return Illuminate\Database\Eloquent\Relations\Relation
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }
}
