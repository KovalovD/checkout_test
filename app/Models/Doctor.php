<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Doctor extends Model
{
    protected $fillable = ['name', 'years_of_experience'];

    public function specializations(): BelongsToMany
    {
        return $this->belongsToMany(Specialization::class, 'doctors_specializations');
    }

    public function networks(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'doctors_network', 'doctor_1_id', 'doctor_2_id');
    }
}
