<?php

namespace App\Models;

use Database\Factories\MapelFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['kode', 'nama'])]
class Mapel extends Model
{
    /** @use HasFactory<MapelFactory> */
    use HasFactory;
    
    protected $table = 'mapel';
}