<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PesananController extends Controller
{
  public function index(Request $request): View
  {
    $status = $request->input('status', 'semua');

    $query = Pesanan::with(['pembeli', 'penjual'])->orderByDesc('id_pesanan');

    if (array_key_exists($status, Pesanan::STATUS)) {
      $query->where('status', $status);
    } else {
      $status = 'semua';
    }

    return view('admin.pesanan.index', [
      'pesanan' => $query->paginate(15)->withQueryString(),
      'status' => $status,
      'jumlah' => Pesanan::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status'),
    ]);
  }

  public function show(Pesanan $pesanan): View
  {
    $pesanan->load(['pembeli', 'penjual']);

    return view('admin.pesanan.show', compact('pesanan'));
  }
}
