<?php

namespace App\Models\Rappel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class rappel extends Model
{
    use HasFactory ;

    protected $table = "rappels";

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'contact',
        'titre',
        'date',
        'heure',
        'jour'
    ];
}
