<?php

namespace App\Models;

use App\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLog extends Model
{
    use HasFactory, UuidTrait;

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
}
