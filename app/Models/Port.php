<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    protected $fillable = ['port_name', 'country_code', 'latitude', 'longitude', 'index_number'];

    protected $appends = ['country_name'];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }

    public function getCountryNameAttribute(): string
    {
        if ($this->relationLoaded('country') && $this->country) {
            return $this->country->name;
        }
        
        // Fallback to countries table if not loaded
        $country = \App\Models\Country::where('code', $this->country_code)->first();
        return $country?->name ?? $this->country_code;
    }
}
