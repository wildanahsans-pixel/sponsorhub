<?php
namespace App\Http\Controllers;

use App\Models\Sponsor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function landing() { return auth()->check() ? redirect()->route('dashboard') : view('welcome'); }
    public function show() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nama_perusahaan' => ['required','string','max:100'],
            'kontak_person' => ['required','string','max:100'],
            'nomor_telepon' => ['required','string','max:20'],
            'email' => ['required','email','max:100','unique:users,email','unique:sponsors,email'],
            'password' => ['required','confirmed', Password::min(8)],
        ]);

        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['kontak_person'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => 'Perusahaan',
            ]);
            Sponsor::create([
                'user_id' => $user->id,
                'nama_sponsor' => $data['nama_perusahaan'],
                'kontak_person' => $data['kontak_person'],
                'email' => $data['email'],
                'nomor_telepon' => $data['nomor_telepon'],
                'kategori' => 'Umum',
                'status_kerja_sama' => 'Prospek',
            ]);
            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route('dashboard')->with('success','Registrasi berhasil. Lengkapi pilihan paket dan benefit perusahaan Anda.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate(['email' => ['required','email'], 'password' => ['required']]);
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }
        return back()->withErrors(['email' => 'Email atau password tidak sesuai.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
