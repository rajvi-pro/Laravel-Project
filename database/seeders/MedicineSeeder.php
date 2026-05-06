<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;
use Carbon\Carbon;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $medicines = [
            [
                'name' => 'Amoxicillin',
                'generic_name' => 'Amoxicillin',
                'manufacturer' => 'PharmaCorp',
                'description' => 'Antibiotic used to treat bacterial infections',
                'category' => 'Antibiotics',
                'unit_price' => 12.50,
                'stock_quantity' => 500,
                'expiry_date' => Carbon::now()->addYears(2)
            ],
            [
                'name' => 'Lisinopril',
                'generic_name' => 'Lisinopril',
                'manufacturer' => 'MediCare Inc',
                'description' => 'Used to treat high blood pressure',
                'category' => 'Cardiovascular',
                'unit_price' => 8.75,
                'stock_quantity' => 350,
                'expiry_date' => Carbon::now()->addYears(1)
            ],
            [
                'name' => 'Metformin',
                'generic_name' => 'Metformin Hydrochloride',
                'manufacturer' => 'HealthPlus',
                'description' => 'Used to treat type 2 diabetes',
                'category' => 'Diabetes',
                'unit_price' => 15.00,
                'stock_quantity' => 600,
                'expiry_date' => Carbon::now()->addMonths(18)
            ],
            [
                'name' => 'Ibuprofen',
                'generic_name' => 'Ibuprofen',
                'manufacturer' => 'PainRelief Ltd',
                'description' => 'Pain reliever and anti-inflammatory',
                'category' => 'Pain Relief',
                'unit_price' => 6.25,
                'stock_quantity' => 800,
                'expiry_date' => Carbon::now()->addYears(3)
            ],
        ];

        foreach ($medicines as $medicine) {
            Medicine::create($medicine);
        }
    }
}