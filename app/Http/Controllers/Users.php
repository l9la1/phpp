<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\patient;



class Users extends Controller
{
    // Display a listing of users
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // Show the form for creating a new user
    public function create()
    {
        return view('users.create');
    }

    // Store a newly created user in storage
    public function store(Request $request)
    {
        // Validate form input
        $validatedData = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'perms' => 'required|string', // Permissions field, e.g., 'patient'
            ],
            [
                'name.required' => 'De naam veld is verplicht',
                'email.required' => 'Het email veld is verplicht',
                'email.unique' => 'Het email is al in gebruik',
                'password.required' => 'Het wachtwoord is een verplicht veld',
                'password.min' => 'Het wachtwoord moet minimaal 8 characters lang zijn',
                'perms.required' => 'Het permissies veld is verplicht',
            ]
        );

        // Create user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']), // Hash the password
            'perms' => '2',
        ]);

        return redirect()->route('users.index')->with('success', 'User successfully created.');
    }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Find the user by email
        $user = User::where('email', $validatedData['email'])->first();

        // Check if the user exists and if the password matches
        if ($user && Hash::check($validatedData['password'], $user->password)) {
            // Manually log in the user (e.g., by setting session data)

            $request->session()->put('user_id', $user->id); // Customize session management as needed
            $request->session()->put('perm', $user->perms);
            $request->session()->put('name', $user->name);
            $request->session()->regenerate();

            if ($user->perms == 0) {
                return redirect()->route('docter.index');
            } else if ($user->perms == 1) {
                return redirect()->route('administrator.index', 'client');
            } else if ($user->perms == 2) {
                $approval_state = patient::where('email', $validatedData['email'])->first("approval_state");
                if ($approval_state == '0') {
                    return redirect()->back();
                } else {
                    return redirect()->route('patient.index');
                }
            }
        }

        // If credentials don't match
        return back()->withErrors([]);
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.create');
    }

    // Display the specified user
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // Show the form for editing the specified user
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Update the specified user in storage
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'perms' => 'required|string',
            ]
        );

        // Update user details
        $user->update($validatedData);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // Remove the specified user from storage
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
