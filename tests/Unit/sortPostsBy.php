<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\ApexCom;
use App\Models\Block;
use App\Models\Post;

class sortPostsBy extends TestCase
{
    use WithFaker;

    /**
     * Just a helper fuction to test that the posts are sorted correctly
     *
     * @param string $apexComID    the apexComID that contains the posts
     * @param array  $posts        the posts itself
     * @param string $sortingParam the param that the posts are sorted by in the database
     *
     * @return bool
     */
    private function _checkPosts($apexComID, $posts, $sortingParam)
    {
        for ($i=0; $i < count($posts)-1; $i++) {
            if ($apexComID !== null && $posts[$i]['apex_id'] !== $apexComID) {
                return false;
            };

            if ($posts[$i][$sortingParam] < $posts[$i+1][$sortingParam]) {
                return false;
            }
        }
        return true;
    }

    
    /**
     * Test sorting the posts by valid sortingParam.
     *
     * Assumes that there are some recordes in the database
     *
     * @test
     *
     * @return void
     */
    public function validSort()
    {
        $apexComID = ApexCom::inRandomOrder()->firstOrFail()->id;
        $sortingParams = [
            'date' => 'created_at', 'votes' => 'votes', 'comments' => 'comments_count'
        ];
        foreach ($sortingParams as $sortingParam => $sortedColumn) {
            $response = $this->json(
                'GET',
                '/api/sort_posts',
                [
                    'apexComID' => $apexComID,
                    'sortingParam' => $sortingParam
                ]
            );
            $response->assertStatus(200);
            $posts = $response->json('posts');
            $this->assertTrue(
                $this->_checkPosts($apexComID, $posts, $sortedColumn)
            );
        }
    }

    /**
     * Tests userSortPostsBy
     * Assumes that there are some records in the database
     * 
     * @test
     *
     * @return void
     */
    public function userSort()
    {
        $loginResponse = $this->json(
            'POST',
            '/api/sign_in',
            ['username' => 'Monda Talaat', 'password' => 'monda21']
        );
        $token = $loginResponse->json('token');
        $userID = $loginResponse->json('user')['id'];

        $response = $this->json('POST', '/api/sort_posts', compact('token'));
        $posts = $response->json('posts');

        //check that there are no posts from blocked users or hidden posts or reported posts
        $posts = $response->json('posts');
        foreach ($posts as $post) {
            $postWriterID = $post['posted_by'];
            $this->assertFalse(Block::areBlocked($userID, $postWriterID));
            $this->assertDatabaseMissing(
                'apex_blocks',
                ['ApexID' => $post['apex_id'], 'blockedID' => $userID]
            );
            $this->assertDatabaseMissing(
                'hiddens',
                ['postID' => $post['id'], 'userID' => $userID]
            );
            $this->assertDatabaseMissing(
                'report_posts',
                ['postID' => $post['id'], 'userID' => $userID]
            );
        }

        $sortingParams = [
            'date' => 'created_at', 'votes' => 'votes', 'comments' => 'comments_count'
        ];
        foreach ($sortingParams as $sortingParam => $sortedColumn) {
            $response = $this->json(
                'POST',
                '/api/sort_posts',
                compact('sortingParam', 'token')
            );
            $response->assertStatus(200);
            $posts = $response->json('posts');
            $this->assertTrue(
                $this->_checkPosts(null, $posts, $sortedColumn)
            );
        }

    }

    /**
     * Test sorting the posts with no apexComID (it uses the default (null)).
     * Assumes that there are some recordes in the database
     *
     * @test
     *
     * @return void
     */
    public function noApexCom()
    {
        $sortingParams = [
            'date' => 'created_at', 'votes' => 'votes', 'comments' => 'comments_count'
        ];
        foreach ($sortingParams as $sortingParam => $sortedColumn) {
            $response = $this->json(
                'GET',
                '/api/sort_posts',
                [
                    'sortingParam' => $sortingParam
                ]
            );
            $response->assertStatus(200);
            $posts = $response->json('posts');
            $this->assertTrue(
                $this->_checkPosts(null, $posts, $sortedColumn)
            );
        }
    }


    /**
     * Test invalid apexComID.
     *
     * @test
     *
     * @return void
     */
    public function invalidApexComID()
    {
        $response = $this->json(
            'GET',
            '/api/sort_posts',
            [
            'apexComID' => '-1',
            'sortingParam' => 'votes'
            ]
        );
        $response->assertStatus(404);
    }

    /**
     * Test invalid sortingParam.
     * it will use the default parameter 'date'
     *
     * Assumes that there are some recordes in the database
     * 
     * @test
     *
     * @return void
     */
    public function invalidSortingParam()
    {
        $apexComID = apexCom::inRandomOrder()->firstOrFail()->id;
        $response = $this->json(
            'GET',
            '/api/sort_posts',
            [
            'apexComID' => $apexComID,
            'sortingParam' => 'something'
            ]
        );
        $response->assertStatus(200);
        $posts = $response->json('posts');
        $this->assertTrue(
            $this->_checkPosts($apexComID, $posts, 'created_at')
        );
    }

    /**
     * Test no given sortingParam.
     * it will use the default parameter 'date'
     *
     * Assumes that there are some recordes in the database
     *
     * @test
     *
     * @return void
     */
    public function noSortingParam()
    {
        $apexComID = ApexCom::inRandomOrder()->firstOrFail()->id;
        $response = $this->json(
            'GET',
            '/api/sort_posts',
            [
            'apexComID' => $apexComID,
            ]
        );
        $response->assertStatus(200);
        $posts = $response->json('posts');
        $this->assertTrue(
            $this->_checkPosts($apexComID, $posts, 'created_at')
        );
    }
}
