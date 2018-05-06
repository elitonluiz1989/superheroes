<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Superhero extends Model
{
    protected $fillable = [
        'nickname',
        'real_name',
        'origin_description',
        'superpowers',
        'catch_phrase'
    ];

    public function images()
    {
        return $this->hasMany('App\SuperheroImage', 'superhero_id', 'id');
    }

    public function getAvatarAttribute()
    {
        return count($this->images) > 0 ? $this->images_list->random() : route('superheroes.image', 'anonymous.png', false);
    }

    public function getImagesListAttribute()
    {
        return $this->images->map(function($image) {
            return route('superheroes.image', $image->superhero_image, false);
        });
    }
}
