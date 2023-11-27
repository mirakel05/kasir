@extends('layout.layout')
@section('content')
    <div class="content-body">
        <div class="row page-titles mx-0">
            <div class="col p-md-0">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Dasboard</a></li>
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">{{ $title }}</a></li>
                </ol>
            </div>
        </div>
        <!-- row -->

        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <form method="POST" action="/transaksi/store">
                            @csrf
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <h4 class="card-title mt-5">{{ $title }}</h4>
                                </div>
                                <hr />
                                <button class="btn btn-sm btn-primary" type="button" data-target="#modalCreate"
                                    data-toggle="modal">
                                    <i class="fa fa-plus"></i> Tambah Data
                                </button>
                                <hr />
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>No</th>
                                            <th>Barang</th>
                                            <th>Harga</th>
                                            <th>Qty</th>
                                            <th>Subtotal</th>
                                            <th>Action</th>
                                        </tr>
                                        @foreach ($data_cart as $b)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $b->nama_barang }}</td>
                                                <td>{{ $b->harga }}</td>
                                                <td>{{ $b->qty }}</td>
                                                <td>Rp. {{ number_format($b->qty * $b->harga) }}</td>
                                                <td>
                                                    <a href="/cart/destroy/{{ $b->id }}"
                                                        class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Hapus</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4">Total</td>
                                            <td>Rp. {{ number_format($subtotal) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">Diskon</td>
                                            <td>Rp. {{ number_format($diskon) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">Total Bayar</td>
                                            <td>Rp. {{ number_format($subtotal - $diskon) }}</td>
                                            <td class="d-none"><span id="totalbayar">{{ $subtotal - $diskon }}</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <input type="hidden" value="{{ $subtotal - $diskon }}" name="total">
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save
                                    Changes</button>
                                <a href="/transaksi" class="btn btn-danger"><i class="fa fa-undo"></i> Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- #/ container -->
    </div>
@endsection
