<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuperheroSaveRequest;
use App\Repositories\SuperheroRepository;
use Illuminate\Http\Request;

class SuperheroesController extends Controller
{
    private $superhero;

    public function __construct(SuperheroRepository $superhero)
    {
        $this->superhero = $superhero;
        $this->superhero->limit = 5;
    }

    public function index()
    {
        $superheroes = $this->superhero->get();

        $data = [
            'superheroes' => $superheroes,
            'pagination' => [
                'links' => $superheroes->links()
            ]
        ];

        return view('home', $data);
    }

    public function get(int $id)
    {
        try {
            $superhero = $this->superhero->get($id);

            return response()->json($superhero);
        } catch (\Exception $err) {
            return response()->json(['error' => $err->getMessage()]);
        }
    }

    public function save(SuperheroSaveRequest $request)
    {
        try {
            $data = $request->validated();
            $superhero = $this->superhero->save($data);

            return response()->json($superhero);
        } catch (\Exception $err) {
            return response()->json(['error' => $err->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $id = $request->validate([
                'id' => 'integer|required'
            ])['id'];

            $superhero = $this->superhero->delete($id);

            return response()->json($superhero);
        } catch (\Exception $err) {
            return response()->json(['error' => $err->getMessage()]);
        }
    }
}
