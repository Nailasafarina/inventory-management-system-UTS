<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $suppliers = [
            [
                'name' => 'CV Kopi Nusantara Mandiri',
                'phone' => '081234567890',
                'email' => 'order@kopinusantara.id',
                'address' => 'Jl. Kebun Kopi No. 18, Malang, Jawa Timur',
            ],
            [
                'name' => 'PT Segar Sentosa Beverage',
                'phone' => '081987654321',
                'email' => 'sales@segarsentosa.co.id',
                'address' => 'Kawasan Industri Pergudangan Rungkut Blok B-5, Surabaya',
            ],
            [
                'name' => 'UD Cemilan Berkah Nusantara',
                'phone' => '082112345678',
                'email' => 'distribusi@cemilanberkah.com',
                'address' => 'Jl. Sentra Keripik No. 88, Bandung, Jawa Barat',
            ],
            [
                'name' => 'PT Boga Manis Bakery & Pastry',
                'phone' => '085712345678',
                'email' => 'supply@bogamanis.com',
                'address' => 'Jl. Malioboro Kuliner Blok A-12, Yogyakarta',
            ],
        ];

        foreach ($suppliers as $sup) {
            Supplier::firstOrCreate(
                ['name' => $sup['name']],
                $sup
            );
        }
    }
}
