<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;

    //Se usa ORM con el nombre de la tabla y sus atributos que pueden ser llenados
    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
    ];

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
