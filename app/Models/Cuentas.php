<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuentas extends Model
{
    use HasFactory;
    protected $table = 'cuentas';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
