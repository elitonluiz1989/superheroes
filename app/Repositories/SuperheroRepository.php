<?php

namespace App\Repositories;

use App\Superhero;
use App\SuperheroImage;

class SuperheroRepository
{
    public $limit = 0;

    private $superheroImage;

    public function __construct(SuperheroImageRepository $superheroImage)
    {
        $this->superheroImage = $superheroImage;
    }

    public function get($id = null)
    {
        if (null != $id) {
            return Superhero::with('images')->find($id);
        } else {
            return Superhero::with('images')
                            ->select('superheroes.id', 'superheroes.nickname')
                            ->paginate($this->limit);
        }
    }

    public function save(array $data)
    {
        if (isset($data['id'])) {
            $id = $data['id'];
            unset($data['id']);

            $superhero = Superhero::find($id);
        } else {
            $superhero = new Superhero();
        }

        if (isset($data['images'])) {
            $images = $data['images'];
            unset($data['images']);
        }

        if (isset($data['origin_description'])) {
            $superhero->origin_description = strip_tags($data['origin_description']);
            unset($data['origin_description']);
        }

        if (isset($data['superpowers'])) {
            $superhero->superpowers = strip_tags($data['superpowers']);
            unset($data['superpowers']);
        }

        foreach ($data as $field => $value) {
            $superhero->$field = $value;
        }

        $saved = $superhero->save();
        if ($saved) {
            if (isset($images)) {
                $images['superhero_id'] = $superhero->id;

                return $this->superheroImage->save($images);
            }
        } else {
            if (isset($images)) {
                $this->superheroImage->delete($images);
            }
        }

        return $saved;
    }

    public function delete(int $id)
    {
        $superheroImages = SuperheroImage::select('id')
                                        ->where('superhero_id', $id)
                                        ->get();

        $deleted = Superhero::destroy($id);
        if ($deleted) {
            foreach ($superheroImages as $image) {
                $id = $image->id;

                $this->superheroImage->destroy($id);
            }
        }

        return $deleted;
    }
}