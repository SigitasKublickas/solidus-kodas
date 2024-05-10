<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Knygos, vadovėliai', 'img_url' => 'knygos-vadoveliai.png', 'path' => 'knygos-vadoveliai', 'parent_id' => null],
            ['name' => 'Biuras ir kanceliarija', 'img_url' => 'biuras-kanceliarija.png', 'path' => 'biuras-kanceliarija', 'parent_id' => null],
            ['name' => 'Gyvūnų prekės', 'img_url' => 'gyvunu-prekes.png', 'path' => 'gyvunu-prekes', 'parent_id' => null],
            ['name' => 'Sportas, laisvalaikis', 'img_url' => 'sportas-laisvalaikis.png', 'path' => 'sportas-laisvalaikis', 'parent_id' => null],
            ['name' => 'Automobilių prekės', 'img_url' => 'automobiliu-prekes.png', 'path' => 'automobiliu-prekes', 'parent_id' => null]
        ];

        $parent_ids = [];
        foreach ($categories as &$category) {
            $created_category = Category::create($category);
            // Store the ID of the created category with its name as the key
            $parent_ids[$category['name']] = $created_category->id;
        }

        $child_categories = [
            ['name' => 'Atvirukai', 'img_url' => 'atvirukai.png', 'path' => 'atvirukai', 'parent_id' => 'Knygos, vadovėliai'],
            ['name' => 'Mokomieji vadovėliai', 'img_url' => 'mokomieji-vadoveliai.png', 'path' => 'mokomieji-vadoveliai', 'parent_id' => 'Knygos, vadovėliai'],
            ['name' => 'Vaikų knygos', 'img_url' => 'vaiku-knygos.png', 'path' => 'vaiku-knygos', 'parent_id' => 'Knygos, vadovėliai'],
            ['name' => 'Grožinė literatūra', 'img_url' => 'grozine-literatura.png', 'path' => 'grozine-literatura', 'parent_id' => 'Knygos, vadovėliai'],


            ['name' => 'Rašymo priemonės', 'img_url' => 'rasymo-priemones.png', 'path' => 'rasymo-priemones', 'parent_id' => 'Biuras ir kanceliarija'],
            ['name' => 'Biuro reikmenys', 'img_url' => 'biuro-reikmenys.png', 'path' => 'biuro-reikmenys', 'parent_id' => 'Biuras ir kanceliarija'],
            ['name' => 'Spalvoti popieriai', 'img_url' => 'spalvoti-popieriai.png', 'path' => 'spalvoti-popieriai', 'parent_id' => 'Biuras ir kanceliarija'],
            ['name' => 'Segtuvai', 'img_url' => 'segtuvai.png', 'path' => 'segtuvai', 'parent_id' => 'Biuras ir kanceliarija'],


            ['name' => 'Kačių prekės', 'img_url' => 'kaciu-prekes.png', 'path' => 'kaciu-prekes', 'parent_id' => 'Gyvūnų prekės'],
            ['name' => 'Šunų prekės', 'img_url' => 'sunu-prekes.png', 'path' => 'sunu-prekes', 'parent_id' => 'Gyvūnų prekės'],
            ['name' => 'Paukščių prekės', 'img_url' => 'pauksciu-prekes.png', 'path' => 'pauksciu-prekes', 'parent_id' => 'Gyvūnų prekės'],
            ['name' => 'Žuvų prekės', 'img_url' => 'zuvu-prekes.png', 'path' => 'zuvu-prekes', 'parent_id' => 'Gyvūnų prekės'],


            ['name' => 'Krepšinis', 'img_url' => 'krepsinis.png', 'path' => 'krepsinis', 'parent_id' => 'Sportas, laisvalaikis'],
            ['name' => 'Futbolas', 'img_url' => 'futbolas.png', 'path' => 'futbolas', 'parent_id' => 'Sportas, laisvalaikis'],
            ['name' => 'Tenisas', 'img_url' => 'tenisas.png', 'path' => 'tenisas', 'parent_id' => 'Sportas, laisvalaikis'],
            ['name' => 'Bėgimas', 'img_url' => 'begimas.png', 'path' => 'begimas', 'parent_id' => 'Sportas, laisvalaikis'],

            ['name' => 'Kvepalai', 'img_url' => 'kvepalai.png', 'path' => 'kvepalai', 'parent_id' => 'Automobilių prekės'],
            ['name' => 'Valymo šluostės', 'img_url' => 'valymo-sluostes.png', 'path' => 'valymo-sluostes', 'parent_id' => 'Automobilių prekės'],
            ['name' => 'Telefono laikikliai', 'img_url' => 'telefono-laikikliai.png', 'path' => 'telefono-laikikliai', 'parent_id' => 'Automobilių prekės'],
        ];

        foreach ($child_categories as &$category) {
            if (isset($parent_ids[$category['parent_id']])) {
                $category['parent_id'] = $parent_ids[$category['parent_id']];
            }
            Category::create($category);
        }
    }
}
