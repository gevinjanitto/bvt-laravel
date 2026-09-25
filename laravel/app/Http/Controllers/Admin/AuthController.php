<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return Auth::check() ? redirect()->route('admin.dashboard') : view('admin.login');
    }

    public function login(Request $r)
    {
        $data = $r->validate(['username' => 'required|string|max:40', 'password' => 'required|string|max:72']);
        $key = 'login:' . $r->ip() . ':' . strtolower($data['username']);
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages(['username' => 'Too many failed attempts. Try again in 15 minutes.']);
        }
        if (!Auth::attempt(['username' => strtolower($data['username']), 'password' => $data['password']], true)) {
            RateLimiter::hit($key, 900);
            throw ValidationException::withMessages(['username' => 'Invalid username or password']);
        }
        RateLimiter::clear($key);
        $r->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function account()
    {
        return view('admin.account', ['user' => Auth::user()]);
    }

    public function updateAccount(Request $r)
    {
        $user = Auth::user();
        $data = $r->validate([
            'current_password' => 'required|string|max:72',
            'username' => 'required|string|regex:/^[a-z0-9_.-]{3,40}$/|unique:users,username,' . $user->id,
            'new_password' => 'nullable|string|min:8|max:72|confirmed',
        ]);
        if (!Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages(['current_password' => 'Password saat ini salah']);
        }
        $user->username = strtolower($data['username']);
        if (!empty($data['new_password'])) {
            $user->password = Hash::make($data['new_password']);
        }
        $user->save();
        Auth::logout();
        return redirect()->route('admin.login')->with('status', 'Akun berhasil diperbarui. Masuk dengan akun baru Anda.');
    }

    public function updatePreferences(Request $r)
    {
        $data = $r->validate(['idle_timeout_minutes' => 'required|integer|in:0,5,10,15,30,60,120']);
        User::whereKey(Auth::id())->update($data);
        if ($r->wantsJson()) return response()->json($data);
        return back()->with('status', 'Durasi logout otomatis disimpan.');
    }
}
