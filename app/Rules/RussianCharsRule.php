<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class RussianCharsRule implements ValidationRule
{
    private float $minRussianPercent = 99;
    private string $attributeName;

    public function __construct(float $minRussianPercent = 99, string $attributeName = 'Поле')
    {
        $this->minRussianPercent = $minRussianPercent;
        $this->attributeName = $attributeName;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || empty($value)) {
            $fail("{$this->attributeName} не может быть пустым.");
            return;
        }

        $cleanValue = preg_replace('/[^\p{L}]/u', '', $value);

        if (mb_strlen($cleanValue) === 0) {
            $fail("{$this->attributeName} должно содержать хотя бы одну букву.");
            return;
        }

        $russianChars = preg_match_all('/[а-яА-ЯёЁ]/u', $cleanValue, $matches);
        $totalChars = mb_strlen($cleanValue);

        $russianPercent = ($russianChars / $totalChars) * 100;

        if ($russianPercent < $this->minRussianPercent) {
            $fail("{$this->attributeName} должно содержать не менее {$this->minRussianPercent}% русских символов. Сейчас: " . round($russianPercent, 1) . "%");
        }
    }
}
