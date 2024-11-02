<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{

    protected $table = 'communes';
    protected $fillable = [
        'id_reg',
        'description',
        'status'
    ];

    protected $primaryKey = 'id_com';

    public $timestamps = false;

    public function region()
    {
        return $this->belongsTo(Region::class, 'id_reg');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, ['id_com', 'id_reg']);
    }
}
