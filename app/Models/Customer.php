<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //Se usa ORM con el nombre de la tabla y sus atributos que pueden ser llenados
    protected $table = 'customers';
    protected $fillable = [
        'dni',
        'id_reg',
        'id_com',
        'email',
        'name',
        'last_name',
        'address',
        'date_reg',
        'status'
    ];

    //Se indica que la primarykey no será "id", sino "dni" 
    protected $primaryKey = 'dni';

    //Se desactiva timestamps debido a que, acorde al script facilitado, estos no tienen created_at y updated_at 
    public $timestamps = false;

    // Relación con otros modelos
    public function region()
    {
        return $this->belongsTo(Region::class, 'id_reg');
    }

    public function commune()
    {
        return $this->belongsTo(Commune::class, 'id_com');
    }
}
