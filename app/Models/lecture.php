<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lecture extends Model
{
    use HasFactory;

    protected $table = "lectures";

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'contact',
        'nomAdmin',
        'desc'
    ];
}
