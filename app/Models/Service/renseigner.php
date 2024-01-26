<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class renseigner extends Model
{
    use HasFactory,HasApiTokens;

    protected $table = "renseigners";

    protected $fillable = ['nom','prenom','contact','email','details','type','couts','status'];
}
