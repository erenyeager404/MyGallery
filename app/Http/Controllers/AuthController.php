<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ── Register ────────────────────────────────
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Handle email sudah ada tapi belum verifikasi
        $existing = User::where('email', $request->email)->first();
        if ($existing) {
            if ($existing->hasVerifiedEmail()) {
                return redirect()->route('landing')
                    ->withErrors(['email' => 'Email sudah terdaftar. Silahkan login.'])
                    ->withInput($request->only('name', 'email'));
            }
            // Hapus akun lama yang belum diverifikasi
            $existing->delete();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice')
            ->with('success', 'Registrasi berhasil! Cek email untuk verifikasi.');
    }

    // ── Login ────────────────────────────────────
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (
            !Auth::attempt(
                $request->only('email', 'password'),
                $request->boolean('remember')
            )
        ) {
            return redirect()->route('landing')
                ->withErrors(['email' => 'Email atau password salah.'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        if (!auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Email belum diverifikasi. Cek inbox kamu.');
        }

        auth()->user()->update(['last_login_at' => now()]);

        return redirect()->intended(
            auth()->user()->is_admin
            ? route('admin.dashboard')
            : route('dashboard')
        );
    }

    // ── Logout ───────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing');
    }

    // ── Google OAuth ─────────────────────────────
    public function redirectToGoogle()
    {
        return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('landing')
                ->withErrors(['email' => 'Login Google gagal, coba lagi.']);
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            $user->update([
                'google_id' => $googleUser->getId(),
                'email_verified_at' => $user->email_verified_at ?? now(),
            ]);
        } else {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => null,
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user);
        $user->update(['last_login_at' => now()]);

        return redirect()->intended(
            $user->is_admin ? route('admin.dashboard') : route('dashboard')
        );
    }
}