<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommitteeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $committeeData = [
            ['name' => 'Président', "created_at" => date('Y-m-d H:i:s'), "updated_at" => date('Y-m-d H:i:s')],
            ['name' => 'Comité Bureau Exécutif', "created_at" => date('Y-m-d H:i:s'), "updated_at" => date('Y-m-d H:i:s')],
            ['name' => 'Comité Conseil Core Exécutif', "created_at" => date('Y-m-d H:i:s'), "updated_at" => date('Y-m-d H:i:s')],
            ['name' => 'Comité Communication et Marketing Digital', "created_at" => date('Y-m-d H:i:s'), "updated_at" => date('Y-m-d H:i:s')],
            ['name' => 'Comité Développement Digital', "created_at" => date('Y-m-d H:i:s'), "updated_at" => date('Y-m-d H:i:s')],
            ['name' => 'Comité Formation Recherche et Innovation Digitale', "created_at" => date('Y-m-d H:i:s'), "updated_at" => date('Y-m-d H:i:s')],
            ['name' => 'Comité Encadrement Digital', "created_at" => date('Y-m-d H:i:s'), "updated_at" => date('Y-m-d H:i:s')],
            ['name' => 'Comité Conseil Juridiques Ressources Humaines & Relation Extérieur', "created_at" => date('Y-m-d H:i:s'), "updated_at" => date('Y-m-d H:i:s')],
            ['name' => 'Comité D’activités Socio-Digitales', "created_at" => date('Y-m-d H:i:s'), "updated_at" => date('Y-m-d H:i:s')],
        ];
        DB::table('committees')->insert($committeeData);
    }
}
