<?php

namespace UserAdmin\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $table = 'posts';

    protected $guarded = [];

    public static function newFake()
    {
        return new static([
            'title'   => 'Title ' . Str::random(),
            'content' => implode(' ', array_fill(0, 50, Str::random())),
        ]);
    }
}
