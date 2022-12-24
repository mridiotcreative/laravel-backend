<?php

use Illuminate\Database\Seeder;
use App\Models\Settings;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::truncate();
        // $data = array(
        //     'description' => "Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. sed ut perspiciatis unde sunt in culpa qui officia deserunt mollit anim id est laborum. sed ut perspiciatis unde omnis iste natus error sit voluptatem Excepteu

        //                     sunt in culpa qui officia deserunt mollit anim id est laborum. sed ut perspiciatis Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. sed ut perspi deserunt mollit anim id est laborum. sed ut perspi.",
        //     'short_des' => "Praesent dapibus, neque id cursus ucibus, tortor neque egestas augue, magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.",
        //     'photo' => "image.jpg",
        //     'logo' => 'logo.jpg',
        //     'address' => "NO. 342 - London Oxford Street, 012 United Kingdom",
        //     'email' => "info@vlpl.com",
        //     'phone' => "+060 (800) 801-582",
        // );

        $data=array(
            array(
                'keys_data'=>'Short_Description',
                'values_data'=>'Praesent dapibus, neque id cursus ucibus, tortor neque egestas augue, magna eros eu erat. Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus.',
                'type'=>1,
            ),
            array(
                'keys_data'=>'Description',
                'values_data'=>'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. sed ut perspiciatis unde sunt in culpa qui officia deserunt mollit anim id est laborum. sed ut perspiciatis unde omnis iste natus error sit voluptatem Excepteu sunt in culpa qui officia deserunt mollit anim id est laborum. sed ut perspiciatis Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. sed ut perspi deserunt mollit anim id est laborum. sed ut perspi.',
                'type'=>1,
            ),
            array(
                'keys_data'=>'Logo',
                'values_data'=>'',
                'type'=>2,
            ),
            array(
                'keys_data'=>'Photo',
                'values_data'=>'',
                'type'=>2,
            ),
            array(
                'keys_data'=>'Address',
                'values_data'=>'NO. 342 - London Oxford Street, 012 United Kingdom',
                'type'=>3,
            ),
            array(
                'keys_data'=>'Email',
                'values_data'=>'info@vlpl.com',
                'type'=>3,
            ),
            array(
                'keys_data'=>'Phone_Number',
                'values_data'=>'+060 (800) 801-582',
                'type'=>3,
            )
        );
        
        DB::table('settings')->insert($data);
    }
}