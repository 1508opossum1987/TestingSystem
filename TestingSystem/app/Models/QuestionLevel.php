<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Relations\HasMany;
>>>>>>> ac87530918e011c3b5528caafa7b9655965a1ff1

class QuestionLevel extends Model
{
    /** @use HasFactory<\Database\Factories\QuestionLevelFactory> */
    use HasFactory;
<<<<<<< HEAD
=======

    protected $fillable = [
        'level'
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'level_id');
    }

    public function tests(): HasMany
    {
        return $this->hasMany(Test::class, 'level_id');
    }
>>>>>>> ac87530918e011c3b5528caafa7b9655965a1ff1
}
