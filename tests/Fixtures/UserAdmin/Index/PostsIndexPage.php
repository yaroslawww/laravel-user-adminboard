<?php

namespace UserAdmin\Tests\Fixtures\UserAdmin\Index;

use Illuminate\Http\Request;
use UserAdmin\Http\Requests\BulkActionRequest;
use UserAdmin\IndexPage\Actions\InlineActions\RedirectInlineAction;
use UserAdmin\Tests\Fixtures\UserAdmin\Index\Resources\PostResource;

class PostsIndexPage extends PostsWithoutOverridingIndexPage
{
    public static string $name = 'posts';

    public ?string $identifier = null;

    /**
     * @inerhitDoc
     */
    public string $responseResource = PostResource::class;

    /**
     * @inheritDoc
     */
    public function responsePerPage(Request $request, $query): ?int
    {
        return 20;
    }

    public function bulkActionHandle(BulkActionRequest $request)
    {
        return [
            'result' => 'success',
            'data'   => [
                'actionName'  => $request->actionName(),
                'actionItems' => $request->actionItems(),
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function inlineActions(): array
    {
        return [
            (new RedirectInlineAction('edit'))
                ->setIcon('<icon-edit class="h-4"></icon-edit>')
                ->setTitle('Edit'),
            (new RedirectInlineAction('preview'))
                ->setIcon('<icon-external-link class="h-4"></icon-external-link>')
                ->setTitle('Preview'),
        ];
    }
}
