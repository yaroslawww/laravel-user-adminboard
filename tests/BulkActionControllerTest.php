<?php

namespace UserAdmin\Tests;

use UserAdmin\Tests\Fixtures\Models\Post;

class BulkActionControllerTest extends TestCase
{
    /** @test */
    public function postsRequest()
    {
        Post::newFake()->save();
        Post::newFake()->save();

        $response = $this->getJson(route('user-admin-index-page.bulk-action', [
            config('user-adminboard.pagename_key') => 'posts',
            'group'                                => 'pageGroup',
            'name'                                 => 'pageName',
            'items'                                => Post::get()->pluck('id')->all(),
        ]));

        $response->assertSuccessful();
        $response->assertJsonPath('result', 'success');
        $response->assertJsonPath('data.actionName', 'pageName');
        $this->assertCount(2, $response->json('data.actionItems'));
    }

    /** @test */
    public function postsDefaultsRequest()
    {
        Post::newFake()->save();
        Post::newFake()->save();

        $response = $this->getJson(route('user-admin-index-page.bulk-action', [
            config('user-adminboard.pagename_key') => 'posts-defaults',
            'group'                                => 'pageGroup',
            'name'                                 => 'pageName',
            'items'                                => Post::get()->pluck('id')->all(),
        ]));

        $response->assertStatus(500);
    }

    /** @test */
    public function postsNotAuthorised()
    {
        Post::newFake()->save();
        Post::newFake()->save();

        $response = $this->getJson(route('user-admin-index-page.bulk-action', [
            config('user-adminboard.pagename_key') => 'posts-not-authorised',
            'group'                                => 'pageGroup',
            'name'                                 => 'pageName',
            'items'                                => Post::get()->pluck('id')->all(),
        ]));

        $response->assertForbidden();

        $response = $this->getJson(route('user-admin-index-page.bulk-action', [
            config('user-adminboard.pagename_key') => 'foo-bar',
            'group'                                => 'pageGroup',
            'name'                                 => 'pageName',
            'items'                                => Post::get()->pluck('id')->all(),
        ]));

        $response->assertStatus(500);
    }
}
