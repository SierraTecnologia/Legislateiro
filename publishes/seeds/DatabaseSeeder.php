<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Muleta\Utils\Extratores\FileExtractor;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call([
        //     UsersTableSeeder::class
        // ]);
        // \Legislateiro\Models\User::factory(1)->create([
        //     'email' => "ricardo@ricasolucoes.com.br"
        // ]);

        // if (env('APP_ENV')!=='production') {
        //     \Legislateiro\Models\User::factory(10)->create();
        // }

            $folder = 'modelos';
        

        $modelos = Storage::directories(DIRECTORY_SEPARATOR.'contracts'.DIRECTORY_SEPARATOR.$folder);
// dd($files);
//         $files = Storage::allFiles($folder);
        foreach ($modelos as $modelo) {

            $fileName = FileExtractor::getFileName($modelo);
            dd($fileName, $modelo,
            Str::of($fileName)->kebab()
        );
            // \Legislateiro\Models\TermTemplates
            
        }


/**
 * https://imoveis.mitula.com.br/detalle/372584/1700002604582073582/1/1/aluguel-sitios-petropolis?search_terms=aluguel+sitios+petropolis&page=1&pos=1&t_sec=1&t_or=2&t_pvid=eb39acbc-e652-4d46-8a24-30400c17eb34&req_sgmt=REVTS1RPUDtTRU87U0VSUDs=

https://www.zapimoveis.com.br/imovel/venda-fazenda-sitio-chacara-6-quartos-mobiliado-itaipava-petropolis-rj-6000m2-id-2496310173/

https://www.zapimoveis.com.br/imovel/aluguel-fazenda-sitio-chacara-10-quartos-mobiliado-itaipava-petropolis-rj-1200m2-id-2456383318/

https://www.zapimoveis.com.br/imovel/aluguel-fazenda-sitio-chacara-6-quartos-com-piscina-guaratiba-zona-oeste-rio-de-janeiro-rj-24000m2-id-2503738171/

Sítio em Ilha de Guaratiba, com área de 10.000 M2, mobiliado, totalmente murado, cercado de belas ca
Fazenda/Sítio/Chácara para alugar em

R$ 3.800 /mês
condomínio não informado
IPTU R$ 190

4 banheiros 

Imperdível! Ilha de Garatiba = Sitio localizado no Centro da Ilha de Guaratiba com 24 mil², excelent
Fazenda/Sítio/Chácara para alugar em

R$ 12.000 /mês
condomínio não informado
IPTU R$ 1.000

4 banheiros
24000 m²

6 quartos

3 vagas

10000 m²

3 quartos

2 vagas

 */
    }
}
