<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class codePassword extends Model
{
    use HasFactory,HasApiTokens;

    protected $table = "code_passwords";

    protected $fillable = ['contact','code'];
}
