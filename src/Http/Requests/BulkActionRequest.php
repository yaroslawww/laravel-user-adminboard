<?php

namespace UserAdmin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkActionRequest extends FormRequest
{
    public function rules()
    {
        return [
            config('user-adminboard.pagename_key') => [ 'required', 'string', 'max:100' ],
            'group'                                => [ 'required', 'string', 'max:100' ],
            'name'                                 => [ 'required', 'string', 'max:100' ],
            'items'                                => [ 'required', 'array', 'max:300' ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'items.max' => 'A lot of items selected',
        ];
    }

    public function group(): string
    {
        return $this->input('group');
    }

    public function actionName(): string
    {
        return $this->input('name');
    }

    public function actionItems(): array
    {
        return $this->input('items');
    }
}
