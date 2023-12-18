<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class administrateur extends Model
{
    use HasFactory, Notifiable , HasApiTokens;

    protected $table = "administrateurs";
    protected $fillable = ['nom', 'prenom', 'contact','contact', 'mot_de_passe', 'type'];
}
