<?php

namespace UserAdmin\Http\Controllers;

use UserAdmin\Facades\UserAdmin;
use UserAdmin\Http\Requests\ListRequest;
use UserAdmin\IndexPage\IndexPage;

class ListController
{
    public function __invoke(ListRequest $request)
    {
        /** @var IndexPage $page */
        $page = UserAdmin::indexPage()->resolve($request->input(config('user-adminboard.pagename_key')));

        if (!$page->authorised($request)) {
            abort(403);
        }

        return $page->process($request);
    }
}
