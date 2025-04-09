<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdvisorPosition extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    protected $table = "advisor_position";

    public function advisor(): HasMany
    {
        return $this->hasMany(Advisor::class, 'position_id', 'id');
    }
}
