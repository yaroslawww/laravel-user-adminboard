<?php

namespace UserAdmin\Tests;

use UserAdmin\Tests\Fixtures\UserAdmin\Index\PostsIndexPage;
use UserAdmin\Tests\Fixtures\UserAdmin\Index\PostsWithoutOverridingIndexPage;

class IndexPageTest extends TestCase
{
    /** @test */
    public function has_vue_attributes()
    {
        $value = (new PostsIndexPage())->vueAttributes();

        $this->assertStringContainsString("page-name='posts'", $value);
        $this->assertStringContainsString("identifier=''", $value);
        $this->assertStringContainsString('listing-url=', $value);
        $this->assertStringContainsString('bulk-action-url=', $value);
        $this->assertStringContainsString(':inline-actions=', $value);
        $this->assertStringContainsString(':headers=', $value);

        $value = (new PostsWithoutOverridingIndexPage())->vueAttributes();

        $this->assertStringContainsString("page-name='posts-defaults'", $value);
        $this->assertStringContainsString("identifier='id'", $value);
        $this->assertStringContainsString('listing-url=', $value);
        $this->assertStringContainsString('bulk-action-url=', $value);
        $this->assertStringContainsString(':inline-actions=', $value);
        $this->assertStringContainsString(':headers=', $value);
    }
}
