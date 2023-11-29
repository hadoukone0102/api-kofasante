<?php

namespace App\Models;

use App\Models\ConsultationEnLigne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ConsultantMedical extends Model
{
    use HasFactory, HasApiTokens;
    protected $table = "consultants_medicaux";
    protected $fillable = ['nom', 'prenom', 'specialite', 'badge_scannable'];
    public function consultationsEnLigne() {
        return $this->hasMany(ConsultationEnLigne::class, 'consultant_id');
    }
}
