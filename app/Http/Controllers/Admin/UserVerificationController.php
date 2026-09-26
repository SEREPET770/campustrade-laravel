<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogVerifikasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserVerificationController extends Controller
{
  public function index()
  {
    $users = User::where('role', 'user')
      ->orderByRaw("status_verifikasi = 'menunggu' desc")
      ->paginate(20);

    return view('admin.pengguna.index', compact('users'));
  }

  public function approve(Request $request, User $user)
  {
    $user->update(['status_verifikasi' => 'terverifikasi']);

    LogVerifikasi::create([
      'id_user' => $user->id_user,
      'verified_by' => Auth::id(),
      'status' => 'terverifikasi',
      'catatan' => $request->input('catatan'),
    ]);

    return back()->with('notif', ['pesan' => "User {$user->nama} berhasil diverifikasi.", 'tipe' => 'success']);
  }

  public function reject(Request $request, User $user)
  {
    $request->validate(['catatan' => ['nullable', 'string']]);

    $user->update(['status_verifikasi' => 'ditolak']);

    LogVerifikasi::create([
      'id_user' => $user->id_user,
      'verified_by' => Auth::id(),
      'status' => 'ditolak',
      'catatan' => $request->input('catatan'),
    ]);

    return back()->with('notif', ['pesan' => "User {$user->nama} ditolak.", 'tipe' => 'warning']);
  }
}
