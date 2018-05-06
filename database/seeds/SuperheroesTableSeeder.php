<?php

use Illuminate\Database\Seeder;

class SuperheroesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Superhero::class, 7)->create()->each(function($superhero) {
            $range = rand(1, 4);

            $superhero->images()->saveMany(factory(App\SuperheroImage::class, $range)->create([
                'superhero_id' => $superhero->id
            ]));
        });
    }
}
