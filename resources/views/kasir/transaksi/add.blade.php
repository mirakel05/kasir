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
                                                        class="btn btn-xs btn-danger" onclick="return confirm('Yakin Hapus?')"><i class="fa fa-trash"></i> Hapus</a>
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
                                <hr />
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>No Transaksi</label>
                                            <input type="text" class="form-control" name="no_transaksi" value="NULL"
                                                readonly required>
                                        </div>
                                        <div class="form-group">
                                            <label>Tanggal Transaksi</label>
                                            <input type="text" class="form-control" name="tgl_transaksi" value="{{ date('d/M/Y') }}" readonly
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Uang Pembeli</label>
                                            <input type="number" class="form-control" name="uang_pembeli"
                                                placeholder="Uang Pembeli ..." onkeyup="uangpembeli(this.value)" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Kembalian</label>
                                            <input type="text" class="form-control" id="kembalian" name="kembalian"
                                                placeholder="Kembalian ..." readonly required>
                                        </div>
                                    </div>
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

    <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Data Barang</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <form method="POST" action="/cart/store">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Barang</label>
                            <select class="form-control" name="id_barang" id="jenis_barang" onchange="stok(this.value)"
                                required>
                                @foreach ($data_barang as $b)
                                    <option value="" hidden>---Pilih Barang---</option>
                                    <option value="{{ $b->id }}">{{ $b->nama_barang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="form_stok"></div>
                        {{-- form --}}
                        <div id="tampil_barang"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-undo"></i>
                            Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        const rupiah = (number) => {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR"
            }).format(number);
        }
    </script>
    <script>
        function uangpembeli(uang) {
            kembalian = uang - (+$('#totalbayar').text())
            $('#kembalian').val(rupiah(kembalian).slice(0, -3))
        }
    </script>
    <script>
        function stok(id) {
            $.get('/cek_stok/' + id,
                function(response) {
                    console.log(response);
                    $('#form_stok').html(
                        `<div class="input-group mb-3"><input type="number" name="stok" value="${response.stok}" class="form-control" readonly required><div class="input-group-append"><span class="input-group-text">Pcs</span></div></div><div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">IDR</span></div><input type="number" name="harga" value="${response.harga}" class="form-control" placeholder="Harga ..." readonly required></div><div class="input-group mb-3"><div class="input-group-prepend"><span class="input-group-text">Qty</span></div><input type="number" name="qty" class="form-control" placeholder="Jumlah ..." required></div>`
                    )
                })
        }
    </script>
@endsection
