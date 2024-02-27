<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class documents extends Model
{
    use HasFactory,HasApiTokens;

    protected $table = "documents";

    protected $fillable = [
    "nom",
    "prenom",
    "contact",
    "email",
    "document",
    "rdv",
    "dateRdv",
    "consultVar",
    "typeServices",
    "details",
    "type",
    "couts",
    "status",
    'prog'
    ];
}
