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

    public function storeUserData(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'user_id' => 'required|string|unique:user_data',
            'name' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'pincode' => 'required|integer',
            'email' => 'required|email|unique:user_data',
            'mobile_no' => 'required|digits_between:10,15',
            'messages' => 'nullable|array', // Optional messages array
            'messages.*.id' => 'required_with:messages|integer',
            'messages.*.msg' => 'required_with:messages|string',
            'messages.*.timestamp' => 'required_with:messages|date_format:Y-m-d H:i:s',
        ]);

        // Create new user with messages
        $userData = UserData::create([
            'user_id' => $validatedData['user_id'],
            'name' => $validatedData['name'],
            'state' => $validatedData['state'],
            'city' => $validatedData['city'],
            'country' => $validatedData['country'],
            'pincode' => $validatedData['pincode'],
            'email' => $validatedData['email'],
            'mobile_no' => $validatedData['mobile_no'],
            'messages' => $validatedData['messages'] ?? [], // Default to empty array
        ]);

        return response()->json(['message' => 'User data stored successfully', 'data' => $userData], 201);
    }

    public function updateUserData(Request $request, $user_id)
    {
        // Find the user by ID
        $user = UserData::find($user_id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'state' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
            'country' => 'sometimes|string|max:255',
            'pincode' => 'sometimes|integer',
            'email' => 'sometimes|email|unique:user_data,email,' . $user->id,
            'mobile_no' => 'sometimes|digits_between:10,15',
            'messages' => 'nullable|array', // Optional messages array
            'messages.*.id' => 'required_with:messages|integer',
            'messages.*.msg' => 'required_with:messages|string',
            'messages.*.timestamp' => 'required_with:messages|date_format:Y-m-d H:i:s',
        ]);

        // Update user data
        $user->update(array_filter($validatedData));

        return response()->json(['message' => 'User data updated successfully', 'data' => $user]);
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
