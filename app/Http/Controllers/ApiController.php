<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserData;

class ApiController extends Controller
{
    public function getData()
    {
        // Fetch the first user from the user_data table
        $user = UserData::first(); // Adjust as needed to fetch a specific user

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Build the response using the user data and messages
        $data = [
            "id" => $user->id,
            "user_data" => [
                "user_id" => $user->user_id,
                "name" => $user->name,
                "address" => [
                    "state" => $user->state,
                    "city" => $user->city,
                    "country" => $user->country,
                    "pincode" => $user->pincode,
                ],
                "email" => $user->email,
                "mobile_no" => $user->mobile_no,
            ],
            "message" => collect($user->messages)->map(function ($message) {
                return [
                    "id" => $message['id'],
                    "msg" => $message['msg'],
                    "timestamp" => $message['timestamp'],
                ];
            }),
        ];

        return response()->json($data);
    }

    public function deleteMessage($user_id, $msg_id)
    {
        // Find the user by ID
        $user = UserData::find($user_id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Retrieve messages array
        $messages = $user->messages;

        // Search for the message with the given ID
        $messageIndex = collect($messages)->search(function ($message) use ($msg_id) {
            return $message['id'] == $msg_id;
        });

        if ($messageIndex === false) {
            return response()->json(['error' => 'Message not found'], 404);
        }

        // Remove the message from the array
        array_splice($messages, $messageIndex, 1);

        // Save the updated messages array back to the user
        $user->messages = $messages;
        $user->save();

        return response()->json(['message' => 'Message deleted successfully']);
    }
}
