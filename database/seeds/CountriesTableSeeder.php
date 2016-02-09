<?php

use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\Legit\Countries\Country::class)->create([
            'country_code' => 'olxza',
            'country_iso' => 'ZA',
            'api_key' => 'apikeysouthafrica',
            'status' => 'enabled',
        ]);
    }
}
