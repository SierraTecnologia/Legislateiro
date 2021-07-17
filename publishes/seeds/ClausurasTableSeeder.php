<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Legislateiro\Routines\System\InitData;

class ClausurasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // InitData::users();

        \Legislateiro\Models\TermType::factory(1)->create([
            'code' => "nda",
            'name' => "Contrato de Confidencialidade"
        ]);



        \Legislateiro\Models\TermStage::factory(1)->create([
            'order' => 1,
            'name' => "Partes"
        ]);
        \Legislateiro\Models\TermStage::factory(1)->create([
            'order' => 1,
            'name' => "Partes"
        ]);



        \Legislateiro\Models\TermTemplate::factory(1)->create([
            'name' => "Partes"
        ]);





        \Legislateiro\Models\ParteType::factory(1)->create([
            'name' => "Familia",
            'members' => "Sócios",
        ]);
        \Legislateiro\Models\ParteType::factory(1)->create([
            'name' => "CREDOR(ES)",
            'members' => "CREDOR(ES)",
        ]);
        \Legislateiro\Models\ParteType::factory(1)->create([
            'name' => "DEVEDOR(ES)",
            'members' => "DEVEDOR(ES)",
        ]);

    }
}
