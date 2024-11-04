<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    //Se usa ORM con el nombre de la tabla y sus atributos que pueden ser llenados
    protected $table = 'communes';
    protected $fillable = [
        'id_reg',
        'description',
        'status'
    ];

    //Se indica que la primarykey no será "id", sino "id_com"
    protected $primaryKey = 'id_com';

    //Se desactiva timestamps debido a que, acorde al script facilitado, estos no tienen created_at y updated_at 
    public $timestamps = false;

    // Relación con otros modelos 
    public function region()
    {
        return $this->belongsTo(Region::class, 'id_reg');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, ['id_com', 'id_reg']);
    }
}
