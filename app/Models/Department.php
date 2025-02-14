<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;
    protected $guarded = ['name'];

    public function advisor(): HasMany
    {
        return $this->hasMany(Advisor::class, 'id');
    }
    public function student(): HasMany
    {
        return $this->hasMany(Student::class, 'id');
    }
}
