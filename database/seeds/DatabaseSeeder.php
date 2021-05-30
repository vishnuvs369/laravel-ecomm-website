<?php

use Illuminate\Database\Seeds;


class DatabaseSeeder extends Seeds
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(UsersTableSeeder::class);
         $this->call(SettingTableSeeder::class);
         $this->call(CouponSeeder::class);


    }
}
