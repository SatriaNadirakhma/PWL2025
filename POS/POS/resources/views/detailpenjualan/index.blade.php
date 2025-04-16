@extends('layouts.template')

@section('content')
<div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
            <a href="{{ url('/penjualandetail/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export penjualandetail (PDF)</a>
                <button onclick="modalAction('{{ url('/penjualandetail/import') }}')" class="btn btn-info">Import penjualandetail</button>
                <a href="{{ url('/penjualandetail/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i>Export penjualandetail</a>
                <button onclick="modalAction('{{ url('/penjualandetail/create_ajax') }}')" class="btn btn-success">Tambah Data (Ajax)</button>
            </div>
        </div>
        <div class="card-body">            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            <table class="table table-bordered table-sm table-striped table-hover" id="table-penjualandetail">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>detail_id</th>
                        <th>penjualan_id</th>
                        <th>barang_id</th>
                        <th>harga</th>
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
        var tablepenjualandetail;
        $(document).ready(function() {
            tablepenjualandetail = $('#table-penjualandetail').DataTable({
                processing: true,
                serverSide: true, 
                ajax: {
                    "url": "{{ url('penjualandetail/list') }}",
                    "dataType": "json",
                    "type": "POST",
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
                        data: "detail_id",
                        className: "",
                        width: "10%",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "penjualan_id",
                        className: "",
                        width: "37%",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "barang_id",
                        className: "",
                        width: "10%",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "harga",
                        className: "",
                        width: "10%",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "aksi",
                        className: "text-center",
                        width: "14%",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#table-penjualandetail_filter input').unbind().bind().on('keyup', function(e) {
                if (e.keyCode == 13) { // enter key
                    tablepenjualandetail.search(this.value).draw();
                }
            });
        });
    </script>

@endpush