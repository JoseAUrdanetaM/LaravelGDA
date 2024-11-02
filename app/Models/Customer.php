<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $fillable = [
        'dni',
        'id_reg',
        'id_com',
        'email',
        'name',
        'last_name',
        'address',
        'data_reg',
        'status'
    ];

    protected $primaryKey = 'dni';

    public $timestamps = false;

    public function region()
    {
        return $this->belongsTo(Region::class, 'id_reg');
    }

    public function commune()
    {
        return $this->belongsTo(Commune::class, 'id_com');
    }
}
