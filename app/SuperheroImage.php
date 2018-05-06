<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuperheroImage extends Model
{
    protected $fillable = [
        'superhero_id',
        'superhero_image'
    ];


}
