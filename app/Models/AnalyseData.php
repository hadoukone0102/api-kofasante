<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyseData extends Model
{
    use HasFactory;

    protected $table = "analyse_data";

    protected $fillable = [
        'nom',
        'prenom',
        'contact',
        'email',
        'sexe',
        'age',
        'type',
        'taille',
        'poids',
        'systolique',
        'diastolique',
        'valeurGly',
        'condition',
        'unite',
        'interpretation',
        'conseil',
        'valeurTemp',
    ];

    protected $guarded = [];
}
