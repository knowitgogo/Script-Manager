<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenUsage extends Model
{
    protected $fillable = [
        'token_id',
        'user_id',
        'query',
        'response',
    ];

    public function token()
    {
        return $this->belongsTo(Token::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
