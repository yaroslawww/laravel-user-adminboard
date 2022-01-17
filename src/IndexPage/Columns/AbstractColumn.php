<?php


namespace UserAdmin\IndexPage\Columns;

use Illuminate\Support\Str;

abstract class AbstractColumn
{
    public bool $sortable = false;

    public bool $html = false;

    public string $title;

    public string $field;

    abstract public static function component(): string;

    /**
     * AbstractColumn constructor.
     *
     * @param ...$args - (string $title, string $field, ....)
     *
     * @throws \Exception
     */
    public function __construct(...$args)
    {
        if (empty($args[0]) || !is_string($args[0])) {
            throw new \Exception('Please specify row title');
        }

        $this->title = $args[0];

        $this->field = (!empty($args[1]) && is_string($args[1])) ? $args[1] : Str::snake($args[1]);
    }

    public function sortable(bool $sortable = true): self
    {
        $this->sortable = $sortable;

        return $this;
    }

    public function asHtml(bool $html = true): self
    {
        $this->html = $html;

        return $this;
    }

    public function headerData(): array
    {
        return [
            'component' => static::component(),
            'sortable'  => $this->sortable,
            'title'     => $this->title,
            'field'     => $this->field,
            'html'      => $this->html,
        ];
    }
}
