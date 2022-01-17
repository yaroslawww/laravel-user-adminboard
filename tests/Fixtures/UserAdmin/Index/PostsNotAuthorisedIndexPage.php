<?php

namespace UserAdmin\Tests\Fixtures\UserAdmin\Index;

use Illuminate\Http\Request;
use UserAdmin\Http\Requests\BulkActionRequest;

class PostsNotAuthorisedIndexPage extends PostsWithoutOverridingIndexPage
{
    /**
     * @var string Index page name
     */
    public static string $name = 'posts-not-authorised';

    public function bulkActionHandle(BulkActionRequest $request)
    {
        return [
            'result' => 'success',
        ];
    }

    public function authorised(Request $request): bool
    {
        return false;
    }
}
