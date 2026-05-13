<?php

namespace App\Http\Requests;

use App\Rules\RussianCharsRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TopicStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'name'=> ['required', 'string', 'min:3', 'max:25', 'unique:topics,name', new RussianCharsRule(99, "Название темы")]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'=>'Поле Name обязательно к заполнению!',
            'name.min'=>'Минимальное количество символов 3!',
            'name.max'=>'Максимальное количество символов 25!',
        ];
    }
}
