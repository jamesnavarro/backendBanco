<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bancos extends Model
{
    use HasFactory;
    protected $table = 'bancos';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
