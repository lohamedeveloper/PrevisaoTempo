<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cidades')->insert(
            [
                ['cidade'=>'Berlin','created_at'=>Carbon::now()],
                ['cidade'=>'London','created_at'=>Carbon::now()],
                ['cidade'=>'Paris','created_at'=>Carbon::now()],
                ['cidade'=>'Tokyo','created_at'=>Carbon::now()],
                ['cidade'=>'New York','created_at'=>Carbon::now()]
            ]
        );
    }
}
