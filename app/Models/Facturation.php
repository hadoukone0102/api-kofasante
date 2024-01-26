<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facturation extends Model
{
    use HasFactory;

    protected $table = "facturations";

    protected $fillable = [
        'nom',
        'prenom',
        'contact',
        'email',
        'status',
        'details',
        'type',
        'couts',
        'document',
        'autreTypeDocs',
        'rdv',
        'autreTypeRDV',
        'dateRdv',
        'consultant',
        'tyeConsultation',
        'dateTot',
        'dateTard',
        'services',
        'typeServices',
        'forfait',
        'nombreVisite',
    ];

    protected $guarded = [];
}
