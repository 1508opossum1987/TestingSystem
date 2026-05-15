<?php

namespace App\Http\Requests;

use App\Rules\OptionsStructureRule;
use App\Rules\RussianCharsRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ResultStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'test_id' => 'required|exists:tests,id',
            'score_percent' => 'required|numeric|min:0|max:100',
            'grade' => 'required|integer|in:2,3,4,5',
            'answers' => ['required', 'json', new OptionsStructureRule($this->input('type', 'single_choice'))]
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'Пользователь обязателен',
            'user_id.exists' => 'Выбранная тема не существует',
            'test_id.required' => 'Тест обязателен',
            'test_id.exists' => 'Выбранный тест не существует',
            'score_percent.required' => 'Процент правильных ответов обязателен',
            'score_percent.min' => 'Минимальный процент правильных ответов - 0',
            'score_percent.max' => 'Максимальный процент правильных ответов - 100',
            'grade.required' => 'Оценка обязательна',
            'grade.in' => 'Оценка  может быть от 2 до 5',
            'answers.required' => 'Ответы обязательны',
            'answers.json' => 'Вопросы должны быть в формате json'
        ];
    }
}
