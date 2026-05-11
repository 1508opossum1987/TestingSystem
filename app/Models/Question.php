<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Question extends Model
{
    use HasFactory;


    protected $fillable = [
        'topicId',
        'levelId',
        'questionText',
        'options',
        'correctAnswer',
        'type'
    ];

    public function topic(): belongsTo
    {
        return $this->belongsTo(Topic::class,'topicId');
    }

    public function questionLevel(): belongsTo
    {
        return $this->belongsTo(QuestionLevel::class, 'levelId');
    }

    public function tests(): belongsToMany
    {
        return $this->belongsToMany(Test::class, 'questionTest',
            'questionId', 'testId');
    }
}
