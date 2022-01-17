<?php

namespace UserAdmin\Tests\Fixtures;

use Illuminate\Support\ServiceProvider;
use UserAdmin\Facades\UserAdmin;
use UserAdmin\Tests\Fixtures\UserAdmin\Index\PostsIndexPage;
use UserAdmin\Tests\Fixtures\UserAdmin\Index\PostsNotAuthorisedIndexPage;
use UserAdmin\Tests\Fixtures\UserAdmin\Index\PostsWithoutOverridingIndexPage;

class FixturesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        UserAdmin::indexPage()->usePages([
            PostsIndexPage::class,
            PostsWithoutOverridingIndexPage::class,
            PostsNotAuthorisedIndexPage::class,
        ]);
    }
}
