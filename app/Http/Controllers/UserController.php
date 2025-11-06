<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //
      public function index()
    {
        return response()->json(User::all());
        // $resposnse= User::all();
        // return view('welcome',['user'=>$resposnse]);
    }

    // Show single user
    public function show($id)
    {
        return response()->json(User::findOrFail($id));
    }

    // Create user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string',
            'role' => 'required|in:customer,employee,agent,admin',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create($validated);

        return response()->json($user, 201);
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,'.$id,
            'phone' => 'nullable|string',
            'role' => 'sometimes|in:customer,employee,agent,admin',
            'password' => 'sometimes|string|min:6',
        ]);

        $user->update($validated);

        return response()->json($user);
    }

    // Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }
}
