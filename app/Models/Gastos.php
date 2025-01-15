<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gastos extends Model
{
    use HasFactory;
    protected $table = 'gastos';
    protected $fillable = ['name', 'id_categoria', 'id_users', 'fecha_gasto', 'monto', 'descripcion'];


    public function categoria()
    {
        return $this->belongsTo(Categorias::class, 'id_categoria');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_users');
    }
}
