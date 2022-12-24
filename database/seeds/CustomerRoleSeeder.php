<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customers_roles')->insert([
            'role_id' => 1,
            'customer_id' => 1
        ]);
    }
}
