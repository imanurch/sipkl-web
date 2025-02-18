<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Industry extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'address', 'email', 'phone_num', 'status'];

    public function registration(): HasMany
    {
        return $this->hasMany(Registration::class, 'id');
    }
    public function internship(): HasMany
    {
        return $this->hasMany(Internship::class, 'industry_id', 'id');
    }
}
