<?php

namespace UserAdmin\IndexPage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;
use UserAdmin\Http\Requests\BulkActionRequest;
use UserAdmin\Http\Resources\ListResource;
use UserAdmin\IndexPage\Actions\AbstractInlineAction;
use UserAdmin\IndexPage\Columns\AbstractColumn;

abstract class IndexPage
{
    /**
     * @var string Index page name
     */
    public static string $name = '';

    /**
     * @var string|JsonResource
     */
    public string $responseResource = ListResource::class;

    /**
     * @var string|null
     */
    public ?string $identifier = 'id';

    /**
     * @var bool
     */
    public bool $usePagination = true;

    /**
     * Get page name (class identification).
     *
     * @return string
     */
    public static function name(): string
    {
        return static::$name;
    }

    /**
     * Get columns list.
     *
     * @return array
     */
    abstract public function columns(): array;

    /**
     * @param Request $request
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    abstract public function query(Request $request);

    /**
     * Get listing url.
     *
     * @return string
     */
    public function listingUrl(): string
    {
        if (property_exists($this, 'listingUrl')) {
            return (string) $this->listingUrl;
        }

        return route('user-admin-index-page.list');
    }

    /**
     * Get bulk action url.
     *
     * @return string
     */
    public function bulkActionUrl(): string
    {
        if (property_exists($this, 'bulkActionUrl')) {
            return (string) $this->bulkActionUrl;
        }

        return route('user-admin-index-page.bulk-action');
    }

    /**
     * Update identificator key.
     *
     * @param string|null $key
     *
     * @return $this
     */
    public function useIdentificator(?string $key = null): self
    {
        $this->identifier = (string) $key;

        return $this;
    }

    /**
     * Check is request authorised
     *
     * @param Request $request
     *
     * @return bool
     */
    public function authorised(Request $request): bool
    {
        return true;
    }

    /**
     * @param BulkActionRequest $request
     *
     * @return mixed
     * @throws \Exception
     */
    public function processBulkAction(BulkActionRequest $request)
    {
        $methodName = Str::camel($request->group()) . 'BulkActionHandle';
        if (method_exists($this, $methodName)) {
            return $this->$methodName($request);
        }

        return $this->bulkActionHandle($request);
    }

    /**
     * @param BulkActionRequest $request
     *
     * @return mixed
     * @throws \Exception
     */
    public function bulkActionHandle(BulkActionRequest $request)
    {
        throw new \Exception('Please override method :' . __METHOD__);
    }

    /**
     * Process request
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function process(Request $request)
    {
        $query = $this->query($request);

        return $this->responseResource::collection($this->getResponseCollection($request, $query))
                                      ->additional($this->responseMeta($request, $query));
    }

    /**
     * Default response meta.
     *
     * @param Request $request
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     *
     * @return array
     */
    public function responseMeta(Request $request, $query): array
    {
        return [];
    }

    /**
     * Response collection used in resource.
     *
     * @param Request $request
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    public function getResponseCollection(Request $request, $query)
    {
        $cols = $this->responseColumns($request, $query);
        if ($this->usePagination) {
            $perPage = $this->responsePerPage($request, $query);

            return $query->paginate($perPage, $cols);
        }

        return $query->get($cols);
    }

    /**
     * Get response columns.
     *
     * @param Request $request
     * @param $query
     *
     * @return string[]
     */
    public function responseColumns(Request $request, $query): array
    {
        return [ '*' ];
    }

    /**
     * Get per page value.
     *
     * @param Request $request
     * @param $query
     *
     * @return int|null
     */
    public function responsePerPage(Request $request, $query): ?int
    {
        return null;
    }

    /**
     * @return array|string[]
     */
    public function inlineActions(): array
    {
        return [];
    }

    /**
     * Vue tag attributes.
     *
     * @return string
     */
    public function vueAttributes(): string
    {
        $pageName      = static::name();
        $headers       = json_encode($this->headers());
        $inlineActions = collect($this->inlineActions())->map(fn (AbstractInlineAction $action) => $action->toArray())->toArray();
        $inlineActions = json_encode($inlineActions);

        return "
        page-name='{$pageName}'
        identifier='{$this->identifier}'
        listing-url='{$this->listingUrl()}'
        bulk-action-url='{$this->bulkActionUrl()}'
        :inline-actions='{$inlineActions}'
        :headers='{$headers}'
        ";
    }

    /**
     * Resolved headers data.
     *
     * @return array
     */
    public function headers(): array
    {
        return collect($this->columns())
            ->map(fn (AbstractColumn $row) => $row->headerData())
            ->toArray();
    }
}
