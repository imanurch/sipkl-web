<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Assessment extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'internship_id','industry_score', 'advisor_score', 'final_test_score'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class, 'internship_id');
    }

    public function technical_assessment(): HasMany
    {
        return $this->hasMany(TechnicalAssessment::class, 'assessment_id', 'id');
    }

    public function non_technical_assessment(): HasMany
    {
        return $this->hasMany(NonTechnicalAssessment::class, 'assessment_id', 'id');
    }

    public function final_report_assessment(): HasMany
    {
        return $this->hasMany(FinalReportAssessment::class, 'assessment_id', 'id');
    }

    public function test_assessment(): HasOne
    {
        return $this->hasOne(TestAssessment::class, 'assessment_id', 'id');
    }
}
