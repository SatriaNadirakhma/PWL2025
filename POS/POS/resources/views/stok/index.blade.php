@extends('layouts.template')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a href="{{ url('/stok/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Supplier (PDF)</a>
                <button onclick="modalAction('{{ url('/stok/import') }}')" class="btn btn-info">Import Supplier</button>
                <a href="{{ url('/stok/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i>Export Supplier</a>
                <button onclick="modalAction('{{ url('/stok/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button>
            </div>
        </div>
        <div class="card-body">
            <!-- untuk Filter data -->
            <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_supplier" class="col-md-1 col-form-label">Filter</label>
                            <div class="col-md-3">
                                <select name="filter_supplier" class="form-control form-control-sm filter_supplier">
                                    <option value="">- Semua -</option>
                                    @foreach($barang as $item)
                                        <option value="{{ $item->barang_id }}">{{ $item->barang_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Nama Supplier</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-sm table-striped table-hover" id="table-stok" width="100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Petugas</th>
                        <th>Nama Supplier</th>
                        <th>Nama Barang</th>
                        <th>Tanggal Stok</th>
                        <th>Jumlah Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <div id="myModal" class="modal fade animate shake" tabindex="-1" data-backdrop="static" data-keyboard="false" data-width="75%"></div>
@endsection

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function() {
            $('#myModal').modal('show');
        });
    }
    var tableStok;
    $(document).ready(function() {
        tableStok = $('#table-stok').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('stok/list') }}",
                type: "POST",
                data: function (d) {
                    d.filter_supplier = $('.filter_supplier').val();
                }
            },
            columns: [
                {
                    data: "DT_RowIndex",
                    className: "text-center",
                    width: "5%",
                    orderable: false,
                    searchable: false
                },
                {
                    data: "user.nama",
                    className: "",
                    width: "15%",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "supplier.supplier_nama",
                    className: "",
                    width: "20%",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "barang.barang_nama",
                    className: "",
                    width: "20%",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "stok_tanggal",
                    className: "",
                    width: "15%",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "stok_jumlah",
                    className: "text-right",
                    width: "10%",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "aksi",
                    className: "text-center",
                    width: "15%",
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('#table-stok_filter input').unbind().bind().on('keyup', function(e) {
            if (e.keyCode == 13) {
                tableStok.search(this.value).draw();
            }
        });

        $('.filter_supplier').change(function() {
            tableStok.draw();
        });
    });
</script>
@endpush