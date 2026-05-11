<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    /** @use HasFactory<\Database\Factories\TestFactory> */
    use HasFactory;

    protected $fillable = [
        'levelId',
        'topicId',
        'questionCount',
    ];

    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'resultId');
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'topicId');
    }

    public function questionLevel(): BelongsTo
    {
        return $this->belongsTo(QuestionLevel::class, 'levelId');
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'questionTest',
            'testId','questionId');
    }
}
