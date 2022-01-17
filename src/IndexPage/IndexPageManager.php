<?php

namespace UserAdmin\IndexPage;

use Illuminate\Contracts\Foundation\Application;

class IndexPageManager
{
    /**
     * The application instance.
     */
    protected Application $app;

    /**
     * Allowed pages list.
     * @var array|string[]
     */
    protected array $pages = [];

    /**
     * Resolved page names. Key is name, value is class name
     * @var array
     */
    protected array $resolvedArray = [];

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
     * @param array $pages
     *
     * @return array
     */
    public function usePages(array $pages = [])
    {
        return $this->pages = $pages;
    }

    /**
     * @return string[]
     */
    public function pages(): array
    {
        return $this->pages;
    }

    /**
     * Resolve page class.
     *
     * @param string $name
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function resolve(string $name)
    {
        return $this->app->make($this->getPageByName($name));
    }

    /**
     * Get page class name.
     *
     * @param string $name
     *
     * @return string
     * @throws \Exception
     */
    public function getPageByName(string $name): string
    {
        if (!$this->resolvedArray) {
            $this->resolveArray();
        }
        if (!isset($this->resolvedArray[ $name ])) {
            throw new \Exception("Index page [{$name}] not exists");
        }

        return $this->resolvedArray[ $name ];
    }

    /**
     * Resolve all page names
     */
    protected function resolveArray(): static
    {
        $resolvedArray = [];

        foreach ($this->pages() as $page) {
            if (is_string($page) && is_subclass_of($page, IndexPage::class)) {
                $resolvedArray[ $page::name() ] = $page;
            }
        }

        $this->resolvedArray = $resolvedArray;

        return $this;
    }
}
