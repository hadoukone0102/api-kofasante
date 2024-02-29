<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RapportData extends Model
{
    use HasFactory;

    protected $table = "bilan_rapports";

    protected $fillable = [
        'nom',
        'prenom',
        'contact',
        'email',
        'sexe',
        'age',
        'desc',
        'conseil',
    ];
}
