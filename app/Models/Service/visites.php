<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class visites extends Model
{
    use HasFactory,HasApiTokens;

    protected $table = "visites";

    protected $fillable = ['nom','prenom','contact','email','services','typeServices','details','type','couts','status','autre','typeService'];
}
