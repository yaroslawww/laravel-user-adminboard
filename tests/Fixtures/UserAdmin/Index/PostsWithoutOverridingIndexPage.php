<?php

namespace UserAdmin\Tests\Fixtures\UserAdmin\Index;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use UserAdmin\IndexPage\Columns\TextColumn;
use UserAdmin\IndexPage\IndexPage;
use UserAdmin\Tests\Fixtures\Models\Post;

class PostsWithoutOverridingIndexPage extends IndexPage
{
    /**
     * @var string Index page name
     */
    public static string $name = 'posts-defaults';

    public function columns(): array
    {
        return [
            ( new TextColumn('Title', 'title') )
                ->sortable(),
            // ...
        ];
    }

    public function query(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string', 'max:200'],
        ]);

        $query = Post::query();

        if ($request->has('search')) {
            $text = $request->input('search');
            $query->where(function (Builder $query) use ($text) {
                $query->orWhere('title', 'like', "%{$text}%");
            });
        }

        return $query;
    }
}
