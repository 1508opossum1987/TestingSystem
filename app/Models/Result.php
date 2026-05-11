<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'userId',
        'testId',
        'scorePercent',
        'grade',
        'answers',
        'logFilePath',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function userLog(): HasOne
    {
        return $this->hasOne(UserLog::class, 'resultId');
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class, 'testId');
    }
}
