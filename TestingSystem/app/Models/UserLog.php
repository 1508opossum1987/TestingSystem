<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
=======
use Illuminate\Database\Eloquent\Relations\BelongsTo;
>>>>>>> ac87530918e011c3b5528caafa7b9655965a1ff1

class UserLog extends Model
{
    /** @use HasFactory<\Database\Factories\UserLogFactory> */
    use HasFactory;
<<<<<<< HEAD
=======

    protected $fillable = [
        'user_id',
        'result_id',
        'file_path',
        'content_preview',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class, 'result_id');
    }
>>>>>>> ac87530918e011c3b5528caafa7b9655965a1ff1
}
