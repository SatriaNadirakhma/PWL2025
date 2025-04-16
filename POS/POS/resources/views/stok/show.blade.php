@empty($stok)
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">Data tidak ditemukan.</div>
        </div>
    </div>
</div>
@else
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Data Stok</h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered table-sm">
                <tr>
                    <th width="30%">Nama Supplier</th>
                    <td>{{ $stok->supplier->supplier_nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Nama Barang</th>
                    <td>{{ $stok->barang->barang_nama ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Tanggal Masuk</th>
                    <td>{{ $stok->stok_tanggal }}</td>
                </tr>
                <tr>
                    <th>Jumlah</th>
                    <td>{{ $stok->stok_jumlah }}</td>
                </tr>
                <tr>
                    <th>Dicatat Oleh</th>
                    <td>{{ $stok->user->nama ?? '-' }}</td>
                </tr>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
        </div>
    </div>
</div>
@endempty