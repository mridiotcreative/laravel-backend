<?php

use App\Models\State;
use Illuminate\Database\Seeder;

class StateTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        State::insert([
            ['name' => 'Assam'],
            ['name' => 'Bihar'],
            ['name' => 'Maharashtra'],
            ['name' => 'Gujarat'],
        ]);
    }
}
