<?php

namespace Database\Seeders;

use App\Models\Strand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StrandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fieldStrand = Strand::FIELD_STRAND;
        $fieldDesc   = Strand::FIELD_STRAND_DESC;

        $seed =
            [
                [$fieldStrand => 'ABM',     $fieldDesc => 'Accountancy, Business and Management'],
                [$fieldStrand => 'STEM',    $fieldDesc => 'Science, Technology, Engineering, and Mathematics'],
                [$fieldStrand => 'HUMMS',   $fieldDesc => 'Humanities and Social Science'],
                [$fieldStrand => 'GAS',     $fieldDesc => 'General Academic Strand'],
            ];

        DB::table('strands')->insert($seed);
    }
}
