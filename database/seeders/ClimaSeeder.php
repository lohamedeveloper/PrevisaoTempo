<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClimaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('climas')->insert(
            [
                [
                    'cidade_id'=>1,
                    'temperatura' => '17.55',
                    'sensacao_termica' => '17.71',
                    'descricao' => 'chuva leve',
                    'data' => '02-09-2022',
                    'created_at'=>Carbon::now()
                ],
                [
                    'cidade_id'=>2,
                    'temperatura' => '14.55',
                    'sensacao_termica' => '14.71',
                    'descricao' => 'chuva leve',
                    'data' => '02-09-2022',
                    'created_at'=>Carbon::now()
                ],
               
            ]
        );
    }
}
