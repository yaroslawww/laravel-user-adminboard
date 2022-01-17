<?php

namespace UserAdmin\Tests;

use UserAdmin\Tests\Fixtures\Models\Post;

class ListControllerTest extends TestCase
{
    /** @test */
    public function postsRequest()
    {
        Post::newFake()->save();
        Post::newFake()->save();

        $response = $this->getJson(route('user-admin-index-page.list', [
            config('user-adminboard.pagename_key') => 'posts',
        ]));

        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'title',
                    'actions_meta' => [
                        'edit' => [
                            'url',
                        ],
                        'preview' => [
                            'url',
                        ],
                    ],
                ],
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'per_page',
                'to',
                'total',
            ],
        ]);

        $response->assertJsonPath('meta.current_page', 1);
        $response->assertJsonPath('meta.last_page', 1);
        $response->assertJsonPath('meta.from', 1);
        $response->assertJsonPath('meta.per_page', 20);
        $response->assertJsonPath('meta.to', 2);
        $response->assertJsonPath('meta.total', 2);
        $this->assertCount(2, $response->json('data'));
    }

    /** @test */
    public function postsDefaultsRequest()
    {
        Post::newFake()->save();
        Post::newFake()->save();

        $response = $this->getJson(route('user-admin-index-page.list', [
            config('user-adminboard.pagename_key') => 'posts-defaults',
        ]));

        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'title',
                ],
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'per_page',
                'to',
                'total',
            ],
        ]);

        $response->assertJsonPath('meta.current_page', 1);
        $response->assertJsonPath('meta.last_page', 1);
        $response->assertJsonPath('meta.from', 1);
        $response->assertJsonPath('meta.per_page', 15);
        $response->assertJsonPath('meta.to', 2);
        $response->assertJsonPath('meta.total', 2);
        $this->assertCount(2, $response->json('data'));
    }

    /** @test */
    public function postsNotAuthorised()
    {
        Post::newFake()->save();
        Post::newFake()->save();

        $response = $this->getJson(route('user-admin-index-page.list', [
            config('user-adminboard.pagename_key') => 'posts-not-authorised',
        ]));

        $response->assertForbidden();

        $response = $this->getJson(route('user-admin-index-page.list', [
            config('user-adminboard.pagename_key') => 'foo-bar',
        ]));

        $response->assertStatus(500);
    }
}
