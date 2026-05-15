<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Test extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'level_id',
        'topic_id',
        'question_count',
    ];

    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'result_id');
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    public function question_level(): BelongsTo
    {
        return $this->belongsTo(QuestionLevel::class, 'level_id');
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'question_test',
            'test_id','question_id');
    }
}
