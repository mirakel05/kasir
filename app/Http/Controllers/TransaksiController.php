<?php

namespace App\Http\Controllers;

use App\Models\JenisBarang;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\Cart;
use App\Models\Diskon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        $data = array(
            'title' => 'Data Transaksi',
            'data_transaksi' => Transaksi::all(),
        );

        return view('kasir.transaksi.list', $data);
    }

    public function create(Request $request)
    {
        $data = array(
            'title' => 'Data Transaksi',
            'data_barang' => Barang::all(),
        );

        return view('kasir.transaksi.add', $data);
    }

    public function cek_stok(Barang $barang)
    {
        return response()->json($barang);
    }

    public function store(Request $request)
    {
        $where = ['id_user' => Auth::id(), 'no_transaksi' => NULL];
        $nomorTransaksi = 'TR-' . date('Ymd') . '-' . date('His');
        $transaksi = Transaksi::create([
            'no_transaksi'  => $nomorTransaksi,
            'tgl_transaksi' => now(),
            'diskon'        => Diskon::first()->diskon,
            'kembalian'     => preg_replace("/[^0-9]/", '', $request->kembalian),
            'uang_pembeli'  => $request->uang_pembeli,
            'total_bayar'   => $request->total,
        ]);

        if ($transaksi) {
            Cart::where($where)->update([
                'no_transaksi' => $nomorTransaksi,
            ]);
            return redirect('/transaksi')->withSuccess('Berhasil Ditambah!');
        } else {
            return back()->withInput()->withErrors('Gagal Ditambah!');
        }
    }

    public function show($id)
    {
        return view('kasir.transaksi.detail');
    }
}
