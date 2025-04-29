<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Internship extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['group_id', 'industry_id', 'advisor_id', 'start_date', 'end_date', 'batch_id'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class, 'industry_id');
    }
    public function advisor(): BelongsTo
    {
        return $this->belongsTo(Advisor::class, 'advisor_id');
    }
    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }
    public function internDocument(): HasMany
    {
        return $this->hasMany(InternDocument::class, 'internship_id');
    }
    public function monitoring(): HasMany
    {
        return $this->hasMany(Monitoring::class, 'id');
    }
    public function logbook(): HasMany
    {
        return $this->hasMany(Logbook::class, 'id');
    }
}
