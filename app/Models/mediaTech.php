<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class mediaTech extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = "media_teches";

    protected $fillable = ['id_admin','titre','categorie','media','desc'];
}
