<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Settings::create([
            'site_title' => 'Laravel Ecommerce',
            'company_name' => 'Laravel Ecommerce',
            'email' => 'admin@admin.com',
            'phone_no_1' => '(123) 456-7890',
            'address' => 'Lorem Street, Abc road',
            'service_charges' => 1,
            'tax' => 1,
            'stripe_charges' => 0.30,
            'paypal_charges' => 0.30,
            'splitit_charges' => 1,
        ]);
    }
}
