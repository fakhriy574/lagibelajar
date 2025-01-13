<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Karyawan;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register'); // Ganti dengan nama view yang Anda gunakan
    }

    // Fungsi untuk memproses registrasi
    public function register(Request $request){
   $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',  // Pastikan email unik di database
        'password' => 'required|min:8|confirmed', // Password minimal 8 karakter dan konfirmasi password
    ]);

    // Buat user baru dan simpan data
    $user = new Karyawan();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = Hash::make($request->password); // Hash password sebelum disimpan
    $user->save();

    // Lakukan login otomatis setelah registrasi (optional)
    Auth::login($user);

    // Redirect ke halaman setelah sukses registrasi
    return redirect()->route('/dashboard'); // Ganti dengan route tujuan setelah login atau halaman yang diinginkan
}

    // Metode untuk login
    public function prosesLogin(Request $request)
    {
        $request->validate([
            'nik' => 'required',
            'password' => 'required',
        ]);

        // Cek apakah password yang dimasukkan cocok dengan yang ada di database
        $karyawan = Karyawan::where('nik', $request->nik)->first();

        if ($karyawan && Hash::check($request->password, $karyawan->password)) {
            // Jika password cocok, login
            Auth::guard('karyawan')->login($karyawan);
            return redirect()->route('dashboard');
        } else {
            // Jika login gagal
            return back()->withErrors(['password' => 'NIK atau password salah.']);
        }
    }
}
