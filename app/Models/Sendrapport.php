<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sendrapport extends Model
{
    use HasFactory;

    protected $table = "sendrapports";

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'contact',
        'nomAdmin',
        'titre',
        'desc'
    ];
}
