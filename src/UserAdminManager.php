<?php

namespace UserAdmin;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Route;
use UserAdmin\Http\Controllers\BulkActionController;
use UserAdmin\Http\Controllers\ListController;
use UserAdmin\IndexPage\IndexPageManager;

class UserAdminManager
{

    /**
     * The application instance.
     */
    protected Application $app;

    /**
     * Index page manager instance.
     */
    protected ?IndexPageManager $indexPage = null;

    /**
     * Create a new instance.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Initialize default routes.
     *
     * @param \Closure|null $callback
     * @param \Closure|null $additionalRoutes
     */
    public function routes(?\Closure $callback = null, ?\Closure $additionalRoutes = null)
    {
        $router = Route::prefix('user-admin-index-page');
        if ($callback) {
            $callback($router);
        }
        $router->group(function () use ($additionalRoutes) {
            Route::get('list', ListController::class)
                 ->name('user-admin-index-page.list');
            Route::get('bulk-action', BulkActionController::class)
                 ->name('user-admin-index-page.bulk-action');
            if ($additionalRoutes) {
                $additionalRoutes();
            }
        });
    }

    /**
     * Get index page manager.
     *
     * @return IndexPageManager
     */
    public function indexPage(): IndexPageManager
    {
        if (!$this->indexPage) {
            $this->indexPage = new IndexPageManager($this->app);
        }

        return $this->indexPage;
    }
}
