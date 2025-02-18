<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Advisor extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'nip', 'department_id', 'email', 'phone_num', 'password'];
    protected $hidden = ['password'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function advisorDocument(): HasOne
    {
        return $this->hasOne(AdvisorDocument::class, 'id');
    }
    public function internship(): HasMany
    {
        return $this->hasMany(Internship::class,'advisor_id', 'id');
    }
}
