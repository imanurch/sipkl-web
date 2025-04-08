<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NonTechnicalAssessment extends Model
{
    use HasFactory;
    protected $table = 'non_technical_assessment';
    protected $fillable = ['assessment_id', 'aspect', 'score'];

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class, 'asessment_id');
    }
}
