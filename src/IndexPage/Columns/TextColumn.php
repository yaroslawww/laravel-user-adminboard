<?php


namespace UserAdmin\IndexPage\Columns;

class TextColumn extends AbstractColumn
{
    public static function component(): string
    {
        return 'index-component-text';
    }
}
