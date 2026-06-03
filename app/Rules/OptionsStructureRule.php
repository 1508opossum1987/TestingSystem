<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class OptionsStructureRule implements ValidationRule
{
    private string $type;

    public function __construct(string $type = 'single_choice')
    {
        $this->type = $type;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $options = is_array($value) ? $value : json_decode($value, true);

        if (!is_array($options)) {
            $fail('Поле :attribute должно содержать валидный JSON массив.');
            return;
        }

        if ($this->type === 'single_choice') {
            $this->validateSingleChoice($options, $fail);
        }
        elseif ($this->type === 'multiple_choice') {
            $this->validateMultipleChoice($options, $fail);
        }
        elseif ($this->type === 'true_false') {
            $this->validateTrueFalse($options, $fail);
        }
    }

    private function validateSingleChoice(array $options, Closure $fail): void
    {
        $count = count($options);
        if ($count < 2 || $count > 5) {
            $fail('Для вопроса с выбором ответа нужно от 2 до 5 вариантов. Сейчас: ' . $count);
            return;
        }

        foreach ($options as $key => $option) {
            // Ключи должны быть A, B, C, D, E
            if (!preg_match('/^[A-E]$/', $key)) {
                $fail('Ключи вариантов должны быть буквами A, B, C, D, E. Ошибка в ключе: ' . $key);
                return;
            }

            if (!is_string($option) || trim($option) === '') {
                $fail('Текст варианта :key не может быть пустым.', ['key' => $key]);
                return;
            }

            if (mb_strlen($option) > 255) {
                $fail('Текст варианта :key не должен превышать 255 символов.', ['key' => $key]);
                return;
            }
        }
    }

    private function validateMultipleChoice(array $options, Closure $fail): void
    {
        $count = count($options);
        if ($count < 2 || $count > 5) {
            $fail('Для вопроса с множественным выбором нужно от 2 до 5 вариантов. Сейчас: ' . $count);
            return;
        }

        foreach ($options as $key => $option) {
            if (!preg_match('/^[A-E]$/', $key)) {
                $fail('Ключи вариантов должны быть буквами A, B, C, D, E. Ошибка в ключе: ' . $key);
                return;
            }

            if (!is_string($option) || trim($option) === '') {
                $fail('Текст варианта :key не может быть пустым.', ['key' => $key]);
                return;
            }

            if (mb_strlen($option) > 255) {
                $fail('Текст варианта :key не должен превышать 255 символов.', ['key' => $key]);
                return;
            }
        }
    }

    private function validateTrueFalse(array $options, Closure $fail): void
    {
        if (count($options) !== 2) {
            $fail('Для вопроса "правда/ложь" нужно ровно 2 варианта ответа.');
            return;
        }

        // Ожидаемые ключи
        $expectedKeys = ['true', 'false'];
        foreach ($expectedKeys as $expectedKey) {
            if (!array_key_exists($expectedKey, $options)) {
                $fail('Для вопроса "правда/ложь" нужны ключи "true" и "false". Отсутствует ключ: ' . $expectedKey);
                return;
            }

            if (!is_string($options[$expectedKey]) || trim($options[$expectedKey]) === '') {
                $fail('Текст для варианта :key не может быть пустым.', ['key' => $expectedKey]);
                return;
            }
        }
    }
}
