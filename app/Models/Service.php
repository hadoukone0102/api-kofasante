<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Service extends Model
{
    use HasFactory,HasApiTokens;

    protected $table = "services";

    protected $fillable = ['service','type_service','prix'];
}
