<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsCache extends Model
{
    // Mengizinkan tabel menerima data dari controller
    protected $table = 'news_cache';

    protected $fillable = [
        'country_id',
        'title',
        'description',
        'source_url',
        'sentiment_result',
        'positive_matches',
        'negative_matches',
        'published_at'
    ];
}