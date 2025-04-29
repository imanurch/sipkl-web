<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdvisorPosition extends Model
{
    use HasFactory;

    protected $table = "advisor_position";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name'];

    public function advisor(): HasMany
    {
        return $this->hasMany(Advisor::class, 'position_id', 'id');
    }
}
