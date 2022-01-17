<?php


namespace UserAdmin\IndexPage\Actions\InlineActions;

use UserAdmin\IndexPage\Actions\AbstractInlineAction;

class RedirectInlineAction extends AbstractInlineAction
{
    public ?string $redirectUrl = null;

    public function __construct(...$args)
    {
        parent::__construct(...$args);

        if (!empty($args[1]) && is_string($args[1])) {
            $this->redirectUrl = $args[1];
        }
    }

    /**
     * @inheritDoc
     */
    public function component(): string
    {
        return 'index-inline-action-redirect';
    }

    public function customGlobalData(): array
    {
        return [
            'url' => $this->redirectUrl,
        ];
    }
}
