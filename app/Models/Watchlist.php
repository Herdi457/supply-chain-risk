<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{
    protected $fillable = [
        'user_id',
        'country_code',
        'country_name',
        'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
