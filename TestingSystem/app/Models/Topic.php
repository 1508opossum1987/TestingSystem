<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Relations\HasMany;
>>>>>>> ac87530918e011c3b5528caafa7b9655965a1ff1

class Topic extends Model
{
    /** @use HasFactory<\Database\Factories\TopicFactory> */
    use HasFactory;
<<<<<<< HEAD
=======

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
>>>>>>> ac87530918e011c3b5528caafa7b9655965a1ff1
}
