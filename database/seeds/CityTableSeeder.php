<?php

use App\Models\City;
use Illuminate\Database\Seeder;

class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::insert([
            ['name' => 'Ahmedabad', 'state_id' => 4],
            ['name' => 'Patana', 'state_id' => 2],
            ['name' => 'Mumbai', 'state_id' => 3],
            ['name' => 'Tangla', 'state_id' => 1],
        ]);
    }
}
