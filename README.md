# Laravel user admin back-end.

![Packagist License](https://img.shields.io/packagist/l/yaroslawww/laravel-user-adminboard?color=%234dc71f)
[![Packagist Version](https://img.shields.io/packagist/v/yaroslawww/laravel-user-adminboard)](https://packagist.org/packages/yaroslawww/laravel-user-adminboard)
[![Total Downloads](https://img.shields.io/packagist/dt/yaroslawww/laravel-user-adminboard)](https://packagist.org/packages/yaroslawww/laravel-user-adminboard)
[![Build Status](https://scrutinizer-ci.com/g/yaroslawww/laravel-user-adminboard/badges/build.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/laravel-user-adminboard/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/yaroslawww/laravel-user-adminboard/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/laravel-user-adminboard/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yaroslawww/laravel-user-adminboard/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yaroslawww/laravel-user-adminboard/?branch=master)

Predefined backend functionality to quicker create custom user admin on laravel.

## Installation

Install the package via composer:

```shell
composer require yaroslawww/laravel-user-adminboard
```

Optionally you can publish the config file with:

```shell
php artisan vendor:publish --provider="UserAdmin\ServiceProvider" --tag="config"
```

## Usage

Define routes

```php
Route::middleware(['auth',])
     ->prefix('dashboard')
     ->group(function () {
         \UserAdmin\Facades\UserAdmin::routes();
         // ....
    })
```

Create Index pages and resources

```php
// app/UserAdmin/Index/PostsIndexPage.php
namespace App\UserAdmin\Index;

use App\UserAdmin\Index\Resources\PostResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use UserAdmin\IndexPage\Actions\InlineActions\RedirectInlineAction;
use UserAdmin\IndexPage\Columns\TextColumn;
use UserAdmin\IndexPage\IndexPage;

class PostsIndexPage extends IndexPage
{
    public static string $name = 'posts';

    public ?string $identifier = null;

    /**
     * @inerhitDoc
     */
    public string $responseResource = PostResource::class;


    public function columns(): array
    {
        return [
            ( new TextColumn('Title', 'title') )
                ->sortable(),
            // ...
        ];
    }

    /**
     * @inheritDoc
     */
    public function responsePerPage(Request $request, $query): ?int
    {
        return 20;
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

    /**
     * @inheritDoc
     */
    public function inlineActions(): array
    {
        return [
            ( new RedirectInlineAction('edit') )
                ->setIcon('<icon-edit class="h-4"></icon-edit>')
                ->setTitle('Edit'),
            ( new RedirectInlineAction('preview') )
                ->setIcon('<icon-external-link class="h-4"></icon-external-link>')
                ->setTitle('Preview'),
        ];
    }
}
```

```php
// app/UserAdmin/Index/Resources/PostResource.php
namespace App\UserAdmin\Index\Resources;

use App\Models\Post;
use UserAdmin\Http\Resources\ListResource;

class PostResource extends ListResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return array
     */
    public function toArray($request)
    {
        /** @var Post $post */
        $post = $this->resource;

        return [
            'id'            => $post->getKey(),
            'title'         => $post->title,
            /* Actions data */
            'actions_meta' => [
                'edit' => [
                    'url' => $post->editUrl(),
                ],
                'preview' => [
                    'url' => $post->frontendUrl(),
                ],
            ],
        ];
    }
}
```

Register index page

```php
class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // ...
        UserAdmin::indexPage()->usePages([
            PostsIndexPage::class,
        ]);
    }
}
```

## Credits

- [![Think Studio](https://yaroslawww.github.io/images/sponsors/packages/logo-think-studio.png)](https://think.studio/) 
