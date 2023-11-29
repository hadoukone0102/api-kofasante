<?php

namespace App\Models;

use App\Models\ConsultationEnLigne;
use App\Models\DemandeDocumentMedical;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Model
{
    use HasFactory, Notifiable , HasApiTokens;

    protected $table = "utilisateurs";
    protected $fillable = ['nom', 'prenom', 'email','contact', 'mot_de_passe', 'role'];

    public function consultationsEnLigne() {
        return $this->hasMany(ConsultationEnLigne::class, 'utilisateur_id');
    }

    public function demandesDocumentsMedicaux() {
        return $this->hasMany(DemandeDocumentMedical::class, 'utilisateur_id');
    }

}
