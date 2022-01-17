<?php


namespace UserAdmin\IndexPage\Actions;

use Illuminate\Support\Facades\Response;

class ActionResponse
{
    public static function message(string $text, string $type = 'success')
    {
        return static::response('message', [
            'content' => $text,
            'toast'   => $type,
        ]);
    }

    public static function redirect(string $url)
    {
        return static::response('redirect', [
            'content' => $url,
        ]);
    }

    public static function openInNewTab(string $url)
    {
        return static::response('new-tab', [
            'content' => $url,
        ]);
    }

    protected static function response(string $action, array $data = [])
    {
        return Response::json(array_merge($data, [
            'action' => $action,
        ]));
    }
}
