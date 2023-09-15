<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:operator1,operator2'
        ]);
    
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = $request->role;
    
        $user->save();
        
        \Log::info("User that should be saved: " . json_encode($user->toArray())); // Debug line
    
        $token = $user->createToken('appToken')->plainTextToken;
        return response()->json(['user' => $user, 'token' => $token], 201);
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
    
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('appToken')->plainTextToken;
            $role = $user->role;  // Get role directly from the user model
            return response()->json(['user' => $user, 'token' => $token, 'role' => $role], 200);
        }
    }
}

    
//     public function login(Request $request)
//     {
//         $request->validate([
//             'email' => 'required|string|email',
//             'password' => 'required|string'
//         ]);

//         if (Auth::attempt($request->only('email', 'password'))) {
//             $user = Auth::user();
//             $token = $user->createToken('appToken')->plainTextToken;
//             $role = $user->getRoleNames()->first(); // Assuming one role per user
//             return response()->json(['user' => $user, 'token' => $token, 'role' => $role], 200);
//         }
//     }

// }
