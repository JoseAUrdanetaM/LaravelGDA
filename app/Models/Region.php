<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

    //Se usa ORM con el nombre de la tabla y sus atributos que pueden ser llenados
    protected $table = 'regions';
    protected $fillable = [
        'description',
        'status'
    ];

    //Se indica que la primarykey no será "id", sino "id_reg" 
    protected $primaryKey = 'id_reg';

    //Se desactiva timestamps debido a que, acorde al script facilitado, estos no tienen created_at y updated_at 
    public $timestamps = false;

    //Llamada de claves tablas donde se encuentran llaves foráneas
    public function communes()
    {
        return $this->hasMany(Commune::class, 'id_reg');
    }
}
