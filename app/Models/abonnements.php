<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class abonnements extends Model
{
    use HasFactory,HasApiTokens;

    protected $table = "abonnements";

    protected $fillable = [
    'nom',
    'prenom',
    'contact',
    'email',
    'services',
    'typeServices',
    'autre',
    'typeService',
    'details',
    'type',
    'couts',
    'forfait',
    'nombreVisite',
    'status'
    ];
}
