<?php

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::create(array(
            'firm_name' => 'Customer',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('123456'),
            'state_id' => 1,
            'city_id' => 1,
            'pincode' => 111111,
            'contact_no_1' => '999999999',
            'contact_no_2' => '999999999',
            'gst_no' => '888888888',
            'gst_document' => 'gst.jpg',
            'drug_licence_no' => '00000000',
            'drug_document' => 'drug.jpg',
            'id_proof_document' => 'id_proof.jpg',
            'is_verified' => 1,
            'status' => 1,
        ));
    }
}
