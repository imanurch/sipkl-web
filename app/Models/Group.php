<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Group extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    
    public function registration(): HasOne
    {
        return $this->hasOne(Registration::class, 'group_id');
    }
    public function internship(): HasOne
    {
        return $this->hasOne(Internship::class, 'group_id');
    }
    public function groupMember(): HasMany
    {
        return $this->hasMany(GroupMember::class, 'group_id');
    }
}
