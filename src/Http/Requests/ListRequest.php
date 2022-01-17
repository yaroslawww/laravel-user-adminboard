<?php

namespace UserAdmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListRequest extends FormRequest
{
    public function rules()
    {
        return [
            config('user-adminboard.pagename_key') => ['required', 'string', 'max:100'],
        ];
    }
}
