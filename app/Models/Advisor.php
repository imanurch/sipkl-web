<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Advisor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'name', 'nip', 'position_id', 'level_id', 'department_id', 'phone_num'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function advisorPosition(): BelongsTo
    {
        return $this->belongsTo(AdvisorPosition::class, 'position_id');
    }
    public function advisorLevel(): BelongsTo
    {
        return $this->belongsTo(AdvisorLevel::class, 'level_id');
    }
    public function internship(): HasMany
    {
        return $this->hasMany(Internship::class, 'advisor_id', 'id');
    }
}
