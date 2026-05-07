<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
>>>>>>> ac87530918e011c3b5528caafa7b9655965a1ff1

class Result extends Model
{
    /** @use HasFactory<\Database\Factories\ResultFactory> */
    use HasFactory;
<<<<<<< HEAD
=======

    protected $fillable = [
        'user_id',
        'test_id',
        'score_percent',
        'grade',
        'answers',
        'log_file_path',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function userLog(): HasOne
    {
        return $this->hasOne(UserLog::class, 'result_id');
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class, 'test_id');
    }
>>>>>>> ac87530918e011c3b5528caafa7b9655965a1ff1
}
