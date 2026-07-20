<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiskScore extends Model
{
    protected $table = 'risk_scores';

    protected $fillable = [
        'country_id',
        'weather_risk_score',
        'inflation_risk_score',
        'exchange_rate_risk_score',
        'news_sentiment_risk_score',
        'total_risk_score',
        'risk_level'
    ];

    protected $casts = [
        'weather_risk_score' => 'decimal:2',
        'inflation_risk_score' => 'decimal:2',
        'exchange_rate_risk_score' => 'decimal:2',
        'news_sentiment_risk_score' => 'decimal:2',
        'total_risk_score' => 'decimal:2',
    ];

    /**
     * Append custom attributes to JSON
     */
    protected $appends = ['updated_at_human'];

    /**
     * Get human-readable updated_at
     */
    public function getUpdatedAtHumanAttribute()
    {
        return $this->updated_at ? $this->updated_at->diffForHumans() : 'recently';
    }

    /**
     * Relasi ke model Country (Menghubungkan tabel risk_scores ke countries)
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}