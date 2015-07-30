<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call(UserTableSeeder::class);
        DB::table('users')->insert([
            'name' => 'jack',
            'email' => 'jacktest@gmail.com',
            'avatar' => 'img/user_avatar/1.jpg',
            'description' => 'Hello i\'m jack',
            'following' => 1,
            'followed' => 1,
            'password' => bcrypt('secret')
        ]);

        DB::table('users')->insert([
            'name' => 'Alex',
            'email' => 'Alex@gmail.com',
            'avatar' => 'img/user_avatar/1.jpg',
            'description' => 'Hello i\'m Alex',
            'following' => 1,
            'followed' => 1,
            'password' => bcrypt('secret')
        ]);

        DB::table('images')->insert([
            'user_id'=>1,
            'image_url'=>'img/1.jpg',
            'title'=>'title1',
            'description'=>'Some explain'
        ]);

        DB::table('images')->insert([
            'user_id'=>2,
            'image_url'=>'img/2.jpg',
            'title'=>'title2',
            'description'=>'Some explain2'
        ]);

        DB::table('images')->insert([
            'user_id'=>2,
            'image_url'=>'img/3.jpg',
            'title'=>'title3',
            'description'=>'Some explain3'
        ]);

        DB::table('images')->insert([
            'user_id'=>2,
            'image_url'=>'img/4.jpg',
            'title'=>'title4',
            'description'=>'Some explain4'
        ]);

        DB::table('images')->insert([
            'user_id'=>1,
            'image_url'=>'img/5.jpg',
            'title'=>'title5',
            'description'=>'Some explain5'
        ]);

        DB::table('follows')->insert([
            'user_id'=>1,
            'follow_id'=>2
        ]);

        DB::table('follows')->insert([
            'user_id'=>2,
            'follow_id'=>1
        ]);

        DB::table('comments')->insert([
            'image_id'=>1,
            'user_id'=>1,
            'comment'=> 'nice'
        ]);

        DB::table('comments')->insert([
            'image_id'=>2,
            'user_id'=>1,
            'comment'=> 'nice'
        ]);

        DB::table('comments')->insert([
            'image_id'=>1,
            'user_id'=>2,
            'comment'=> 'good'
        ]);

        DB::table('favorites')->insert([
            'user_id'=>1,
            'image_id'=>1
        ]);

        DB::table('favorites')->insert([
            'user_id'=>1,
            'image_id'=>2
        ]);

        DB::table('favorites')->insert([
            'user_id'=>2,
            'image_id'=>1
        ]);
        
        DB::table('favorites')->insert([
            'user_id'=>2,
            'image_id'=>3
        ]);

        

        Model::reguard();
    }
}
