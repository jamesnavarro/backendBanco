<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentasAsociadas extends Model
{
    use HasFactory;
    protected $table = 'cuentas_asociadas';
    protected $primaryKey = 'id';
    public $timestamps = false;
    
}
