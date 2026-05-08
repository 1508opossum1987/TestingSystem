<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{
    /** @use HasFactory<\Database\Factories\TopicFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'topic_id');
    }

    public function tests(): HasMany
    {
        return $this->hasMany(Test::class, 'topic_id');
    }
}
