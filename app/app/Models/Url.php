<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Url extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var string[][] $fillable
     */
    protected $fillable = [
        'user_id',
        'title',
        'url',
        'long_url',
        'short_url',
        'expires_at',
    ];

    /**
     * @var string[] $dates
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
