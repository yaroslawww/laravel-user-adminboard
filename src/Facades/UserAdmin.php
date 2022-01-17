<?php

namespace UserAdmin\Facades;

use Illuminate\Support\Facades\Facade;

class UserAdmin extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'user-admin';
    }
}
