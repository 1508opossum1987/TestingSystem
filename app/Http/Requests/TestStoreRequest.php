<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TestStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'level_id' => 'required|exists:question_levels,id|in:1,2,3,4',
            'topic_id' => 'required|exists:topics,id',
            'question_count' => 'required|integer|min:5|max:15'
        ];
    }

    public function messages(): array
    {
        return [
            'level_id.required' => 'Выберите уровень сложности',
            'level_id.exists' => 'Выбранный уровень не существует',
            'level_id.in' => 'Уровень должен быть 1, 2, 3 или 4',
            'topic_id.required' => 'Выберите тему вопроса',
            'topic_id.exists' => 'Выбранная тема не существует',
            'question_count.required' => 'количество вопросов обязательно',
            'question_count.min' => 'Текст вопроса должен содержать минимум 5 вопросов',
            'question_count.max' => 'Текст вопроса не должен превышать 15 вопросов',
        ];
    }
}
