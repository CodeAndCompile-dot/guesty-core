<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/client-login/guesty_properties';

    /**
     * Show the registration form only if no users exist.
     * This allows the first admin to register, then blocks public registration.
     */
    public function showRegistrationForm()
    {
        // If any users exist, redirect to login
        if (User::count() > 0) {
            return redirect()->route('login')
                ->with('info', 'Registration is disabled. Please contact an administrator to create an account.');
        }

        return view('auth.register');
    }

    /**
     * Handle a registration request only if no users exist.
     */
    public function register(Request $request)
    {
        // If any users exist, prevent registration
        if (User::count() > 0) {
            return redirect()->route('login')
                ->with('error', 'Registration is disabled. Please contact an administrator to create an account.');
        }

        // Proceed with normal registration for the first admin
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        $this->guard()->login($user);

        return redirect($this->redirectPath())
            ->with('success', 'Welcome! You are the first administrator. You can now manage other admins from the dashboard.');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
