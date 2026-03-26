<?php

namespace App\Http\Requests\Dashboard;

use App\Models\__CRUD_STUDLY_SINGULAR__;
use Illuminate\Foundation\Http\FormRequest;

class __CRUD_STUDLY_SINGULAR__Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return collect(__CRUD_STUDLY_SINGULAR__::fields())->mapWithKeys(function ($field) {
            return [$field['name'] => $field['rules'] ?? ['nullable']];
        })->toArray();
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return __CRUD_STUDLY_SINGULAR__::fieldLabels();
    }
}
