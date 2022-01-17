<?php

namespace UserAdmin\Http\Controllers;

use UserAdmin\Facades\UserAdmin;
use UserAdmin\Http\Requests\BulkActionRequest;
use UserAdmin\IndexPage\IndexPage;

class BulkActionController
{
    public function __invoke(BulkActionRequest $request)
    {

        /** @var IndexPage $page */
        $page = UserAdmin::indexPage()->resolve($request->input(config('user-adminboard.pagename_key')));

        if (!$page->authorised($request)) {
            abort(403);
        }

        return $page->processBulkAction($request);
    }
}
