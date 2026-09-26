<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
  public function showLogin()
  {
    return view('auth.login');
  }

  public function login(Request $request)
  {
    $credentials = $request->validate([
      'email' => ['required', 'email'],
      'password' => ['required'],
    ]);

    $user = User::where('email', $credentials['email'])->first();

    if (! $user || ! $this->checkPassword($credentials['password'], $user)) {
      return back()
        ->withErrors(['email' => 'Email atau password salah.'])
        ->onlyInput('email');
    }

    if ($user->status_verifikasi === 'menunggu') {
      return back()->withErrors(['email' => 'Akun Anda sedang menunggu verifikasi.']);
    }

    if ($user->status_verifikasi === 'ditolak') {
      return back()->withErrors(['email' => 'Akun Anda telah ditolak. Silakan hubungi admin.']);
    }

    Auth::login($user, $request->boolean('remember'));
    $request->session()->regenerate();

    return redirect()->intended(
      $user->isAdmin() ? route('admin.dashboard') : route('user.dashboard')
    )->with('notif', ['pesan' => 'Selamat datang, ' . $user->nama . '!', 'tipe' => 'success']);
  }

  /**
   * Data lama sebagian masih plaintext (bug di aplikasi native).
   * Cek hash bcrypt dulu; kalau gagal, fallback ke plaintext,
   * lalu upgrade otomatis ke bcrypt begitu berhasil login.
   */
  protected function checkPassword(string $plain, User $user): bool
  {
    $sudahBcrypt = (bool) preg_match('/^\$2[axy]\$/', $user->password);

    if ($sudahBcrypt) {
      return Hash::check($plain, $user->password);
    }

    // fallback: password lama disimpan plaintext
    if ($plain === $user->password) {
      $user->forceFill(['password' => Hash::make($plain)])->save();
      return true;
    }

    return false;
  }

  public function showRegister()
  {
    return view('auth.register');
  }

  public function register(Request $request)
  {
    $validated = $request->validate([
      'nama' => ['required', 'string', 'max:100'],
      'email' => ['required', 'email', 'max:100', 'unique:users,email'],
      'password' => ['required', 'string', 'min:8'],
      'nim' => ['required', 'string', 'max:20', 'unique:users,nim'],
      'no_whatsapp' => ['required', 'string', 'max:20'],
      'foto_ktm' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
    ]);

    $path = $request->file('foto_ktm')->store('ktm', 'public');

    User::create([
      'nama' => $validated['nama'],
      'email' => $validated['email'],
      'password' => Hash::make($validated['password']),
      'nim' => $validated['nim'],
      'no_whatsapp' => $validated['no_whatsapp'],
      'foto_ktm' => basename($path),
      'role' => 'user',
      'status_verifikasi' => 'menunggu',
    ]);

    return redirect()->route('login')
      ->with('notif', ['pesan' => 'Registrasi berhasil, menunggu verifikasi admin.', 'tipe' => 'success']);
  }

  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
  }
}
