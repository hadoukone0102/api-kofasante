<?php

namespace App\Models;

use App\Models\Utilisateur;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class DemandeDocumentMedical extends Model
{
    use HasFactory , HasApiTokens;

    protected $fillable = ['utilisateur_id', 'type_document', 'statut', 'lien_document'];

    public function utilisateur() {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }
}
