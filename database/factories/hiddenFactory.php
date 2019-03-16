<?php

use Faker\Generator as Faker;

$factory->define(App\hidden::class, function (Faker $faker) {
    $users = DB::table('users')->pluck('id')->all();
    $posts = DB::table('posts')->pluck('id')->all();
    return [
        'postID'=> $posts[array_rand($posts)],
        'userID'=> factory(App\User::class)->create()
    ];
});