<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Register

    public function showRegister()
    {
        return view('auth.register');

    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',

            'password' => 'required|min:6|confirmed',

        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),

        ]);

        Auth::login($user);
        $user->sendEmailVerificationNotification();
        return redirect()->route('verification.notice')
            ->with('success', 'Registrasi berhasil! Silahkan cek email untuk verifikasi.');
    }

    // Login

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            $request->session()->regenerate();

            // Cek apakah email sudah diverifikasi
            if (!auth()->user()->hasVerifiedEmail()) {
                // Kirim ulang email verifikasi otomatis
                auth()->user()->sendEmailVerificationNotification();

                // Logout dulu biar tidak bisa akses halaman lain
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Redirect ke login dengan pesan peringatan
                return redirect()->route('login')->with(
                    'warning',
                    'Email kamu belum diverifikasi. Kami sudah kirim ulang link verifikasi, cek email kamu.'
                );
                // ↑ Cara ini lebih aman — user harus verifikasi dulu baru bisa login
            }

            // Update waktu login terakhir
            auth()->user()->update(['last_login_at' => now()]);

            // Redirect sesuai role
            if (auth()->user()->is_admin) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }
    // logout

    public function logout(Request $request)
    {
        Auth::logout();
        // ↑ Hapus session login user

        $request->session()->invalidate();
        // ↑ Hancurkan seluruh session yang ada

        $request->session()->regenerateToken();
        // ↑ Buat CSRF token baru untuk keamanan

        return redirect()->route('landing');
    }
}