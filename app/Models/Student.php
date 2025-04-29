<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'nisn',
        'nis',
        'gender',
        'department_id',
        'year',
        'phone_num',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    // one or many?
    public function groupMember(): HasMany
    {
        return $this->hasMany(GroupMember::class, 'student_id');
    }
    public function internDocument(): HasMany
    {
        return $this->hasMany(InternDocument::class, 'student_id');
    }
    public function logbook(): HasMany
    {
        return $this->hasMany(Logbook::class, 'id');
    }
    public function assessment(): HasMany
    {
        return $this->hasMany(Assessment::class, 'id');
    }
}
