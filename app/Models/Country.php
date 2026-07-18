<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['code', 'name', 'currency_code', 'region', 'languages', 'population', 'area'];

    public function riskScore()
    {
        return $this->hasOne(RiskScore::class);
    }
}
