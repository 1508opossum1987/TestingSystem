<?php

namespace App\Http\Requests;

use App\Rules\OptionsStructureRule;
use App\Rules\RussianCharsRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class QuestionStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'topic_id' => 'required|exists;topics,id',
            'level_id' => 'required|exists:question_levels,id|in:1,2,3,4',
            'question_text' => ['required', 'string', 'min:15', 'max:255', 'unique:questions,question_text', new RussianCharsRule(99, 'Текст вопроса')],
            'options' => ['required', 'json', new OptionsStructureRule($this->input('type', 'single_choice'))],
            'correct_answer' => 'required|string|max:255',
            'type' => 'sometimes|in:single_choice,multiple_choice,true_false'
        ];
    }

    public function messages(): array
    {
        return [
            'topic_id.required' => 'Выберите тему вопроса',
            'topic_id.exists' => 'Выбранная тема не существует',
            'level_id.required' => 'Выберите уровень сложности',
            'level_id.exists' => 'Выбранный уровень не существует',
            'level_id.in' => 'Уровень должен быть 1, 2, 3 или 4',
            'question_text.required' => 'Текст вопроса обязателен',
            'question_text.min' => 'Текст вопроса должен содержать минимум 15 символов',
            'question_text.max' => 'Текст вопроса не должен превышать 255 символов',
            'question_text.unique' => 'Такой вопрос уже существует',
            'options.required' => 'Варианты ответов обязательны',
            'options.json' => 'Варианты ответов должны быть в формате JSON',
            'correct_answer.required' => 'Укажите правильный ответ',
            'type.in' => 'Тип вопроса может быть: single_choice, multiple_choice или true_false'
        ];
    }
}
