<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
                   \DB::table('users')->insert(
                array(
                    'name' => 'RESTapi',
                    'username' => 'api',
                    'email' => 'api@api.com',
                    'password' => password_hash('api',PASSWORD_DEFAULT),
                    'updated_at' => date('Y-m-d H:i:s'),
                )
            );
    }
}
