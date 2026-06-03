<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class QuestionLevel extends Model
{
    use HasFactory;

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
}
