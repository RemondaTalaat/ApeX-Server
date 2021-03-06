<?php

use App\Models\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Validator;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    static $i = 1;
    $lastUser = User::withTrashed()
    ->selectRaw('CONVERT( SUBSTR(id, 4), INT) AS intID')->get()->max('intID');
    $Uname = $faker->unique()->userName;
    while (!User::withTrashed()->where('username', $Uname)->get()) {
        $Uname = $faker->unique()->userName;
    }
    $mail = $faker->unique()->safeEmail;
    while (!User::withTrashed()->where('email', $mail)->get()) {
        $mail = $faker->unique()->safeEmail;
    }
    return [
        'id' => 't2_'.(string)($lastUser + $i++),
        'fullname'=>$faker->name,
        'email' =>  $mail,
        'username'=> $Uname,
        'password' => '$2y$10$EFyhgTaTJGLEtHg3ylrJ/eAIoEFZ/UZ4w3/dMF5CF4NteCsB/PcgS',
        'avatar'=>'/storage/avatars/users/t2_3872.jpg',
        'karma'=>1,
        'notification'=>true,
        'type'=>rand(1, 3)
    ];
});
