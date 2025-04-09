<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdvisorLevel extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    protected $table = "advisor_level";

    public function advisor(): HasMany
    {
        return $this->hasMany(Advisor::class, 'level_id', 'id');
    }
}
