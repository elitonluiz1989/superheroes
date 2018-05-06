<?php

namespace App\Repositories;

use App\SuperheroImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class SuperheroImageRepository
{
    public $imagePath = 'images/';

    public function upload(UploadedFile $image)
    {

        $newName = md5(uniqid(rand(), true)) . '.' . $image->extension();

        $image->storeAs($this->imagePath, $newName);

        return route('superheroes.image', ['image' => $newName], false);
    }

    public function delete($images)
    {
        if (is_array($images)) {
            // This case will be when is tried delete unsaved images
            $images = collect($images);
        }

        $files = $images->map(function($imagem) {
            $filename = $this->removePath($imagem);
            $path = $this->imagePath . $filename;

            return $path;
        });

        return Storage::disk(env('FILESYSTEM_DRIVER'))->delete($files->toArray());
    }

    public function destroy(int $id)
    {
        $images = SuperheroImage::select('superhero_image as src')
                                ->where('id', $id)
                                ->get();

        $files = $images->map(function($image) {
            return $image->src;
        });

        if (SuperheroImage::destroy($id)) {
            $this->delete($files);
        }

        return true;
    }

    public function save(array $images)
    {
        $superheroId = $images['superhero_id'];
        unset($images['superhero_id']);

        foreach ($images as $image) {
            if (isset($image['id'])) {
                if (isset($image['delete'])) {
                    $this->destroy($image['id']);
                }
                else {
                    $superheroImage = SuperheroImage::find($image['id']);
                    $oldImage = $superheroImage->superhero_image;

                    $superheroImage->superhero_image = $this->removePath($image['src']);
                    $newImageSaved = $superheroImage->save();


                    if ($newImageSaved) {
                        $this->delete([$oldImage]);
                    } else {
                        $this->delete([$image['src']]);
                    }
                }
            } else {
                $superheroImage = new SuperheroImage();
                $superheroImage->superhero_id = $superheroId;
                $superheroImage->superhero_image = $this->removePath($image);

                $superheroImage->save();
            }
        }

        return true;
    }

    public function removePath(string $image)
    {
        $filename = explode('/', trim($image));
        return $filename[count($filename) - 1];
    }
}