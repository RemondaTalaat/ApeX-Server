<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    protected $fillable = [
      'id',
      'posted_by',
      'apex_id',
      'title',
      'img',
      'videolink',
      'content',
      'locked',
    ];

    protected $appends = [
        'votes', 'comments_count', 'apex_com_name', 'post_writer_username'
    ];
    
    public $incrementing = false;

    public function comments()
    {
        return $this->hasMany(Comment::class, 'root');
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'postID');
    }

    public function getVotesAttribute()
    {
        return (int)$this->votes()->sum('dir');
    }

    public function apexCom()
    {
        return $this->belongsTo(ApexCom::class, 'apex_id');
    }

    public function getApexComNameAttribute()
    {
        return $this->apexCom()->first()['name'];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    public function getPostWriterUsernameAttribute()
    {
        return $this->user()->first()['username'];
    }

    public function userVote($userID)
    {
        return (int)$this->votes()->where(compact('userID'))->first()['dir'];
    }

    public function saves()
    {
        return $this->hasMany(SavePost::class, 'postID');
    }

    //return if the given user saved the post
    public function isSavedBy($userID)
    {
        return $this->saves()->where(compact('userID'))->exists();
    }

    public function hiddens()
    {
        return $this->hasMany(Hidden::class, 'postID');
    }

    //return if the given user hid the post
    public function isHiddenBy($userID)
    {
        return $this->hiddens()->where(compact('userID'))->exists();
    }
}
