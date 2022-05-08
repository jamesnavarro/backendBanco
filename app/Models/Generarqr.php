<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generarqr extends Model
{
    use HasFactory;
    protected $table = 'link_pagos';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
