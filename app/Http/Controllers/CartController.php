<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Cart;
use App\Models\Diskon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $where = ['id_user' => Auth::id(), 'no_transaksi' => NULL];

        $subtotal =  Cart::join('tbl_barang', 'tbl_barang.id', '=', 'tbl_cart.id_barang')
        ->select('tbl_cart.*', 'tbl_barang.nama_barang', 'tbl_barang.harga')->where($where)
        ->sum(DB::raw('tbl_cart.qty * tbl_barang.harga'));

        $diskon = Diskon::first();

        $hargadiskon = 0;

        if($subtotal > $diskon->total_belanja){
            $hargadiskon = $subtotal * $diskon->diskon/100;
        }


            $title = 'Data Transaksi';
            $data_barang = Barang::all();
            $data_cart = Cart::join('tbl_barang', 'tbl_barang.id', '=', 'tbl_cart.id_barang')
                ->select('tbl_cart.*', 'tbl_barang.nama_barang', 'tbl_barang.harga')->where($where)
                ->get();

            $subtotal = $subtotal;

            $diskon = $hargadiskon;


        return view('kasir.transaksi.add', compact('data_cart','data_barang','subtotal','diskon','title'));
    }

    public function store(Request $request)
    {
        Cart::create([
            'id_barang'     => $request->id_barang,
            'id_user'       => Auth::id(),
            'qty'           => $request->qty,
            'no_transaksi'  => NULL,
        ]);

        $barang = Barang::where('id', $request->id_barang)->first();

        if($barang){
            $barang->stok = $barang->stok - $request->qty;
            $barang->save();
            return redirect('/cart/create')->withSuccess('Berhasil Ditambahkan!');
        }else{
            return back('/cart/create')->withErrors('Gagal Ditambahkan!');
        }
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();

        $barang = Barang::where('id', $cart->id_barang)->first();

        if($barang){
            $barang->stok = $barang->stok + $cart->qty;
            $barang->save();
            return redirect('/cart/create')->withSuccess('Berhasil Dihapus!');
        }else{
            return back('/cart/create')->withErrors('Gagal Dihapus!');
        }
    }
}
