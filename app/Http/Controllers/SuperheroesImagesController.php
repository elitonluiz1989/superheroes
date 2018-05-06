<?php

namespace App\Http\Controllers;

use App\Repositories\SuperheroImageRepository;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class SuperheroesImagesController extends Controller
{
    private $superheroImage;

    public function __construct(SuperheroImageRepository $superheroImage)
    {
        $this->superheroImage = $superheroImage;
    }

    public function upload(Request $request) {
        try {
            if ($request->hasFile('image')) {
                $this->validate($request, [
                    'image' => 'image|mimes:jpg,png,jpeg'
                ]);

                $image = $request->file('image');

                return response()->json($this->superheroImage->upload($image));
            } else {
                return response()->json(['error' => 'No image file to upload.']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $this->validate($request, [
                'images' => 'array'
            ]);

            $images = $request->get('images');

            return response()->json($this->superheroImage->delete($images));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function image(Request $request, string $image)
    {
        $path = storage_path('app/public/images/' . $image);

        $img = Image::make($path);

        if ($request->has('width') || $request->has('height')) {
            $width = $request->get('width') ?? $request->get('height');
            $height = $request->get('height') ?? $request->get('width');

            $img->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        $format = $img->extension;

        // Workaround to do the image appear. Before only show a 16x16 transparent square instead of image
        ob_end_clean();

        return $img->response($format);
    }
}
