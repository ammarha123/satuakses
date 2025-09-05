<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'     => ['required', 'string', 'max:255'],
            'gender'     => ['required', 'string', 'max:255'],
            'province'     => ['required', 'string', 'max:255'],
            'city'     => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Normalize email
        $validated['email'] = Str::lower($validated['email']);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'     => $validated['phone'],
            'gender'     => $validated['gender'],
            'province'     => $validated['province'],
            'city'     => $validated['city'],
            'password' => Hash::make($validated['password']),
        ]);

        // If you're using spatie/laravel-permission, set default role
        if (method_exists($user, 'assignRole')) {
            $user->assignRole('user');
        }

        event(new Registered($user));

        Auth::login($user);

        // Redirect by role
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('employer')) {
            return redirect()->route('employer.dashboard');
        }

        // Default: go to homepage
        return redirect()->route('index');
    }
}
