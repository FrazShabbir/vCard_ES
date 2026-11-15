<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run()
    {
        $rows = [
            ['key' => 'personal-1', 'name' => 'Personal', 'profile_type' => 'personal', 'months' => 1, 'price_paisa' => 1400_00, 'badge' => null],
            ['key' => 'personal-12', 'name' => 'Personal', 'profile_type' => 'personal', 'months' => 12, 'price_paisa' => 9800_00, 'badge' => 'Popular'],
            ['key' => 'personal-24', 'name' => 'Personal', 'profile_type' => 'personal', 'months' => 24, 'price_paisa' => 16500_00, 'badge' => 'Best value'],
            ['key' => 'business-1', 'name' => 'Business', 'profile_type' => 'business', 'months' => 1, 'price_paisa' => 2800_00, 'badge' => null],
            ['key' => 'business-12', 'name' => 'Business', 'profile_type' => 'business', 'months' => 12, 'price_paisa' => 19600_00, 'badge' => 'Popular'],
            ['key' => 'business-24', 'name' => 'Business', 'profile_type' => 'business', 'months' => 24, 'price_paisa' => 33000_00, 'badge' => 'Best value'],
        ];

        foreach ($rows as $row) {
            Plan::updateOrCreate(['key' => $row['key']], $row + ['is_active' => true]);
        }

        if (function_exists('setSettings')) {
            setSettings('shop.card.pricePaisa', (string) 2500_00);
            setSettings('orders.shipping.flatPaisa', (string) 550_00);
        }
    }
}
