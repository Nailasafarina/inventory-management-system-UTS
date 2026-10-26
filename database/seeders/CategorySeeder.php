<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Kopi',
                'description' => 'Aneka kopi susu gula aren, kopi tubruk, dan racikan kopi botolan',
            ],
            [
                'name' => 'Minuman Dingin',
                'description' => 'Aneka es teh nusantara, matcha, boba, dan minuman segar kekinian',
            ],
            [
                'name' => 'Cemilan Ringan',
                'description' => 'Keripik kaca, basreng daun jeruk, makaroni pedas, dan pangsit renyah',
            ],
            [
                'name' => 'Cemilan Manis',
                'description' => 'Keripik pisang cokelat lumer, brownies panggang, dan camilan manis legit',
            ],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['name' => $cat['name']],
                $cat
            );
        }
    }
}
