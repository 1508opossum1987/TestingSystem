<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Result extends Model
{
    /** @use HasFactory<\Database\Factories\ResultFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'test_id',
        'score_percent',
        'grade',
        'answers',
        'log_file_path',
    ];

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function userLog(): HasOne
    {
        return $this->hasOne(UserLog::class, 'result_id');
    }

    public function test(): belongsTo
    {
        return $this->belongsTo(Test::class, 'test_id');
    }
}
