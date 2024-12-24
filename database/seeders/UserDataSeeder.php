<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserData;
use Carbon\Carbon;

class UserDataSeeder extends Seeder
{
    public function run()
    {
        // Create sample users with messages
        UserData::create([
            'user_id' => 'dtTaXiairgSsH0ZLKssrmHYZQh23',
            'name' => 'Saransh Shukla',
            'state' => 'Gujarat',
            'city' => 'Ahmedabad',
            'country' => 'India',
            'pincode' => 380009,
            'email' => 'shuklasaransh2002@gmail.com',
            'mobile_no' => '919664753223',
            'messages' => [
                [
                    'id' => 1,
                    'msg' => 'I have a good day',
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'id' => 2,
                    'msg' => 'I have a good day',
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'id' => 3,
                    'msg' => 'New patch message',
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ],
            ],
        ]);

        // Add more users with different messages if needed
        UserData::create([
            'user_id' => 'anotherUserId',
            'name' => 'John Doe',
            'state' => 'California',
            'city' => 'Los Angeles',
            'country' => 'USA',
            'pincode' => 90001,
            'email' => 'johndoe@example.com',
            'mobile_no' => '1234567890',
            'messages' => [
                [
                    'id' => 1,
                    'msg' => 'Hello, how are you?',
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'id' => 2,
                    'msg' => 'I am doing fine, thank you!',
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ],
            ],
        ]);
    }
}
