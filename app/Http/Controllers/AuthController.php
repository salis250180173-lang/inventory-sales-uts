<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Memproses autentikasi login dengan error handling yang aman.
     */
    public function login(Request $request)
    {
        // 1. Validasi input form
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        try {
            // 2. Coba lakukan autentikasi menggunakan Auth bawaan Laravel
            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();

                // Redirect sesuai role masing-masing pengguna
                return redirect()->intended(route('dashboard'))
                    ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
            }

            // 3. Jika gagal (email/password salah), lempar exception agar ditangkap catch
            throw ValidationException::withMessages([
                'email' => ['Email atau password yang Anda masukkan salah.'],
            ]);

        } catch (ValidationException $e) {
            // Error handling khusus validasi login agar kembali ke form dengan pesan rapi
            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors($e->errors());

        } catch (\Exception $e) {
            // Error handling umum jika terjadi kendala sistem
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Memproses logout pengguna.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}
