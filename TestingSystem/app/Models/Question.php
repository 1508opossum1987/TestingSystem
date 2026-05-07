<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
>>>>>>> ac87530918e011c3b5528caafa7b9655965a1ff1

class Question extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionFactory> */
    use HasFactory;
<<<<<<< HEAD
=======

    protected $fillable = [
        'topic_id',
        'level_id',
        'question_text',
        'options',
        'correct_answer',
        'type'
    ];

    public function topic(): belongsTo
    {
        return $this->belongsTo(Topic::class,'topic_id');
    }

    public function questionLevel(): belongsTo
    {
        return $this->belongsTo(QuestionLevel::class, 'level_id');
    }

    public function tests(): belongsToMany
    {
        return $this->belongsToMany(Test::class, 'test_question',
            'question_id', 'test_id');
    }
>>>>>>> ac87530918e011c3b5528caafa7b9655965a1ff1
}
