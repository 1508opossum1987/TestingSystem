<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    /** @use HasFactory<\Database\Factories\TestFactory> */
    use HasFactory;

    protected $fillable = [
        'level_id',
        'topic_id',
        'question_count',
    ];

    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'result_id');
    }
}
