<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Specialization extends Model
{
    protected $fillable = ['specialization'];

    public function doctors(): BelongsToMany
    {
        return $this->belongsToMany(Doctor::class, 'doctors_specializations');
    }
}
