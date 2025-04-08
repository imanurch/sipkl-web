<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolProfile extends Model
{
    use HasFactory;
    protected $table = 'school_profile';
    protected $fillable = ['name', 'address', 'email', 'phone_num', 'website', 'principal_name', 'principal_nip', 'principal_signature', 'school_stamp'];
}
