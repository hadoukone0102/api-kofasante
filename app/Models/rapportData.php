<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rapportData extends Model
{
    use HasFactory;

    protected $table = "rapport_data";

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'contact',
        'droite',
        'gauche',
        'taille',
        'poids',
        'glycemie'
    ];
}
