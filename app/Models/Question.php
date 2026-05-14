<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;


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

    public function question_level(): belongsTo
    {
        return $this->belongsTo(QuestionLevel::class, 'level_id');
    }

    public function tests(): belongsToMany
    {
        return $this->belongsToMany(Test::class, 'question_test',
            'question_id', 'test_id');
    }
}
