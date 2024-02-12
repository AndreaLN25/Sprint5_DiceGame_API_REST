<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    

 public function registerUser(Request $request){
        try {
            $this->validate($request, [
                //'name' => 'required|min:4',
                'email' => 'required|email',
                'password' => 'required|min:8',
            ]);

            $user = User::create([
                'name' => $request->input('name', 'Anonymous'),
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            if (!$user) {
                throw new \Exception('Error creating user');
            }

            $registerMessage ='Register completed';

            return response()->json(['message' => $registerMessage, 'user' => $user], 201);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
}

public function login(Request $request){
    try {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Incorrect credentials.'
            ], 422);
        }
        $token = $user->createToken('API Token')->accessToken;
        return response(['message' => 'User ' . ucfirst($user->name) . ' logged successfully', 'token' => $token, 'user' => $user], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    } 
}

public function logout(){ 
    $user = Auth::user();

    if ($user) {
        $user->tokens->each->revoke();
        return response()->json(['message' => 'Bye!', 'user' => $user->name], 200);
    }
}

public function updateUser(Request $request, $id){
    $userId = Auth::user()->id;
    if ($userId != $id) {
        return response()->json([
            'error' => 'Unauthorized',
            'warning' => 'No permission to update.'
        ], 403);
    }
    User::where('id', $userId)->update(['name'=>$request->name ? $request->name : 'Anonymous']);
    return response()->json([
        'message' => 'User updated successfully',
        'new name' => $request->name ? $request->name : 'Anonymous'
    ], 200);
}

}
