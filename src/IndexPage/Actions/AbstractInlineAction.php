<?php


namespace UserAdmin\IndexPage\Actions;

use Illuminate\Support\Str;

abstract class AbstractInlineAction
{
    public string $name;

    public bool $confirmable = false;

    public string $icon = '';

    public string $title = '';

    abstract public function component(): string;

    /**
     * AbstractInlineAction constructor.
     *
     * @param ...$args - (string $title, string $field, ....)
     *
     * @throws \Exception
     */
    public function __construct(...$args)
    {
        if (empty($args[0]) || !is_string($args[0])) {
            throw new \Exception('Please specify action unique name');
        }

        $this->name  = $args[0];
        $this->title = Str::title(Str::snake($this->name, ' '));
        $this->icon  = Str::limit($this->title, 3);
    }

    /**
     * @return string
     */
    public function name():string
    {
        return $this->name;
    }

    /**
     * Set confirmable.
     *
     * @param bool $confirmable
     *
     * @return $this
     */
    public function confirmable(bool $confirmable = true): self
    {
        $this->confirmable = $confirmable;

        return $this;
    }

    /**
     * @param string $icon
     *
     * @return AbstractInlineAction
     */
    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @param string $title
     *
     * @return AbstractInlineAction
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get custom global data.
     *
     * @return  mixed
     */
    public function customGlobalData():array
    {
        return [];
    }

    /**
     * @return array
     */
    public function toArray():array
    {
        return [
            'component'       => $this->component(),
            'name'            => $this->name(),
            'icon'            => $this->icon,
            'title'           => $this->title,
            'confirmable'     => $this->confirmable,
            'data'            => $this->customGlobalData(),
        ];
    }
}
