<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categorias = [
            'Medicamentos',
            'Dispositivos Medicos',
            'Insumos Quirurgicos',
            'Diagnostico',
            'Material de Curacion',
            'EPP',
            'Desinfectantes',
        ];

        foreach ($categorias as $nombre) {
            DB::table('categorias')->updateOrInsert(
                ['nombre' => $nombre],
                [
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
