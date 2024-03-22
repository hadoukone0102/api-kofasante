<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class type_document extends Model
{
    use HasFactory;
    protected $table = "type_documents";

    protected $fillable = ['type','desc'];
}
