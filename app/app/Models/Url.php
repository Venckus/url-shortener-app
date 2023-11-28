<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class Url extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    public const ID = 'id';
    public const USER_ID = 'user_id';
    public const TITLE = 'title';
    public const LONG_URL = 'long_url';
    public const SHORT_CODE = 'short_code';
    public const EXPIRES_AT = 'expires_at';

    /**
     * @var string[] $fillable
     */
    protected $fillable = [
        self::USER_ID,
        self::TITLE,
        self::LONG_URL,
        self::SHORT_CODE,
        self::EXPIRES_AT,
    ];

    /**
     * @var string[][] $dates
     */
    protected $casts = [
        self::EXPIRES_AT => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
