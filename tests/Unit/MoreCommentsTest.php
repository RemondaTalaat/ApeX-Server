<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Post;

class MoreCommentsTest extends TestCase
{

  /**
   *
   * @test
   *
   * @return void
   */
   //not valid user
    public function guest()
    {
        $post = factory(Post::class)->create();

        $response = $this->json(
            'get',
            '/api/RetrieveComments',
            [
              'parent' => $post['id']
            ]
        );
        $response->assertStatus(200);

        Post::where('id', $post['id'])->delete();
        $this->assertDatabaseMissing('posts', ['id' => $post['id']]);

        User::where('id', $post['posted_by'])->forceDelete();
        $this->assertDatabaseMissing('users', ['id' => $post['posted_by']]);
    }

  /**
   *
   * @test
   *
   * @return void
   */
   //not valid user
    public function AuthRetrieve()
    {
        $user = factory(User::class)->create();
        $post = factory(Post::class)->create();
        $signIn = $this->json(
            'POST',
            '/api/SignIn',
            [
              'username' => $user['username'],
              'password' => 'monda21'
            ]
        );

        $signIn->assertStatus(200);

        $token = $signIn->json('token');
        $response = $this->json(
            'post',
            '/api/RetrieveComments',
            [
              'token' => $token,
              'parent' => $post['id']
            ]
        );
        $response->assertStatus(200);
        
        Post::where('id', $post['id'])->delete();
        $this->assertDatabaseMissing('posts', ['id' => $post['id']]);

        User::where('id', $post['posted_by'])->forceDelete();
        $this->assertDatabaseMissing('users', ['id' => $post['posted_by']]);

        // delete user added to database
        User::where('id', $user['id'])->forceDelete();

        //check that the user deleted from database
        $this->assertDatabaseMissing('users', ['id' => $user['id']]);
    }
}
