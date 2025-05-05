<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $fillable = ['name','code','phone_code'];

    public function states() :HasMany
    {
        return $this->hasMany(State::class);
    }
    public function employees():HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
