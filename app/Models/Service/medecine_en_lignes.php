<?php

namespace App\Models\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class medecine_en_lignes extends Model
{
    use HasFactory,HasApiTokens;

    protected $table = "medecine_en_lignes";

    protected $fillable = [
    'nom'
    ,'prenom','contact',
    'email',
    'consultant',
    'tyeConsultation',
    'autre',
    'dateTot',
    'dateTard',
    'details',
    'type',
    'couts',
    'status'
    ];
}
