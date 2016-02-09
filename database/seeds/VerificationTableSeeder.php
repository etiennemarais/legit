<?php

use Illuminate\Database\Seeder;

class VerificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\Legit\Verification\Verification::class)->create();
    }
}
