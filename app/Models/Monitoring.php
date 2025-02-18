<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Monitoring extends Model
{
    use HasFactory;
    protected $table = 'monitoring';
    protected $fillable = ['internship_id', 'type', 'date', 'note'];

    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class, 'internship_id');
    }
    public function monitoringDocument(): HasMany
    {
        return $this->hasMany(MonitoringDocument::class, 'monitoring_id');
    }
}
