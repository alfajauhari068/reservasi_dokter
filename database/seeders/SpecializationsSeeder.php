<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialization;

/**
 * Seeder for populating doctor specializations
 *
 * Creates initial specialization data with proper PascalCase naming conventions.
 * Includes common medical specialties used in the appointment system.
 *
 * @refactor Confirmed PascalCase naming conventions are properly implemented
 */
class SpecializationsSeeder extends Seeder
{
    public function run(): void
    {
        $specializations = [
            ['name' => 'Umum', 'description' => 'Dokter umum', 'is_active' => true],
            ['name' => 'Jantung', 'description' => 'Spesialis jantung', 'is_active' => true],
            ['name' => 'Mata', 'description' => 'Spesialis mata', 'is_active' => true],
            ['name' => 'Kulit', 'description' => 'Spesialis kulit', 'is_active' => true],
        ];

        foreach ($specializations as $spec) {
            Specialization::updateOrCreate(
                ['name' => $spec['name']],
                $spec
            );
        }
    }
}