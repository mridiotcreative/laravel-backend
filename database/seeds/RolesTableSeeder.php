<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'slug' => 'stockist',
                'name' => 'Stockist',
            ],
            [
                'slug' => 'retailer',
                'name' => 'Retailer',
            ],
            [
                'slug' => 'institute',
                'name' => 'Institute',
            ],
            [
                'slug' => 'doctor',
                'name' => 'Doctor',
            ],
            [
                'slug' => 'mr',
                'name' => 'MR',
            ],
        ];

        Role::insert($roles);
    }
}
