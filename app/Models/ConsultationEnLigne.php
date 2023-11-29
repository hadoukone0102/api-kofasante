<?php

namespace App\Models;

use App\Models\ConsultantMedical;
use App\Models\Utilisateur;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ConsultationEnLigne extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = ['utilisateur_id', 'consultant_id', 'specialite', 'date', 'heure_debut', 'heure_fin', 'lien_consultation', 'statut'];

    public function utilisateur() {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    public function consultant() {
        return $this->belongsTo(ConsultantMedical::class, 'consultant_id');
    }

}
