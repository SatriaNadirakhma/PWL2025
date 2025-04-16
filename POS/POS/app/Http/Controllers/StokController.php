<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\StokModel;
use App\Models\SupplierModel;
use App\Models\UserModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class StokController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Stok',
            'list' => ['Home', 'Stok']
        ];

        $page = (object) [
            'title' => 'Stok yang terdaftar dalam sistem'
        ];

        $activeMenu = 'stok';

        $barang = BarangModel::select('barang_id', 'barang_nama')->get();

        return view('stok.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu]);
    }
    public function list(Request $request)
    {
    $stoks = StokModel::select('stok_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
        ->with(['barang.supplier', 'user']); // Ubah relasi supplier menjadi melalui barang

    $barang_id = $request->input('filter_supplier'); // Ubah nama filter menjadi filter_barang di view nanti
    if (!empty($barang_id)) {
        $stoks->where('barang_id', $barang_id); // Filter berdasarkan barang_id
    }
        
    return DataTables::of($stoks)
        ->addIndexColumn()
        ->addColumn('user.nama', function ($stok) {
            return $stok->user ? $stok->user->nama : '-';
        })
        ->addColumn('barang.barang_nama', function ($stok) {
            return $stok->barang ? $stok->barang->barang_nama : '-';
        })
        ->addColumn('supplier.supplier_nama', function ($stok) {
            return $stok->barang && $stok->barang->supplier ? $stok->barang->supplier->supplier_nama : '-';
        })
        ->addColumn('aksi', function ($stok) {
            $btn =  '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
            $btn .= '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
            $btn .= '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
            return $btn;
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }
    public function create_ajax()
{
    $barang = BarangModel::select('barang_id', 'barang_nama')->get();
    $user = UserModel::select('user_id', 'nama')->get();

    return view('stok.create_ajax')
        ->with('barang', $barang)
        ->with('user', $user);
}

public function store_ajax(Request $request)
{
    if($request->ajax() || $request->wantsJson()) {
        $rules = [
            'barang_id'     => 'required|integer',
            'user_id'       => 'required|integer',
            'stok_tanggal'  => 'required|date',
            'stok_jumlah'   => 'required|integer'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        $user = auth()->user();

        $tanggal = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->stok_tanggal)
                ->format('Y-m-d H:i:s');
        
        StokModel::create([
            'barang_id'     => $request->barang_id,
            'user_id'       => $user->user_id,
            'stok_tanggal'  => $tanggal,
            'stok_jumlah'   => $request->stok_jumlah,
            'created_at'    => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil disimpan'
        ]);
    }
    return redirect('/');
}
public function show_ajax(string $id)
{
    $stok = StokModel::select('stok_id', 'barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
        ->with(['barang.supplier', 'user'])
        ->find($id);

    return view('stok.show_ajax', ['stok' => $stok]);
}

public function edit_ajax(string $id)
{
    $stok = StokModel::find($id);
    $barang = BarangModel::select('barang_id', 'barang_nama')->get();
    $user = UserModel::select('user_id', 'nama')->get();

    return view('stok.edit_ajax', ['stok' => $stok, 'barang' => $barang, 'user' => $user]);
}

public function update_ajax(Request $request, $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'barang_id'     => 'required|integer',
            'stok_tanggal'  => 'required|date',
            'stok_jumlah'   => 'required|integer'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status'   => false,
                'message'  => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        $check = StokModel::find($id);
        if ($check) {
            $check->update([
                'barang_id'     => $request->barang_id,
                'stok_tanggal'  => $request->stok_tanggal,
                'stok_jumlah'   => $request->stok_jumlah,
            ]);
            return response()->json([
                'status'  => true,
                'message' => 'Data berhasil diupdate'
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
    return redirect('/');
}
    public function confirm_ajax(string $id)
    {
        $stok = StokModel::find($id);
        return view('stok.confirm_ajax', ['stok' => $stok]);
    }
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $stok = StokModel::find($id);
            if ($stok) {
                $stok->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }
    public function import()
    {
        return view('stok.import');
    }
    public function import_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'file_stok' => ['required', 'mimes:xlsx', 'max:1024']
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $file = $request->file('file_stok');
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, false, true, true);

        $insert = [];
        if (count($data) > 1) {
            foreach ($data as $baris => $value) {
                if ($baris > 1) {
                    $insert[] = [
                        'barang_id'     => $value['A'], // Kolom A sekarang barang_id
                        'user_id'       => $value['B'], // Kolom B sekarang user_id
                        'stok_tanggal'  => now(),
                        'stok_jumlah'   => $value['C'], // Kolom C sekarang stok_jumlah
                        'created_at'    => now(),
                    ];
                }
            }

            if (count($insert) > 0) {
                StokModel::insertOrIgnore($insert);
                return response()->json([
                    'status' => true,
                    'message' => 'Data stok berhasil diimport'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Tidak ada data yang diimport'
        ]);
    }

    return redirect('/');
}

public function export_excel()
{
    $stok = StokModel::select('barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
        ->orderBy('barang_id')
        ->with(['barang.supplier', 'user'])
        ->get();

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Supplier');
    $sheet->setCellValue('C1', 'Nama Barang');
    $sheet->setCellValue('D1', 'Petugas');
    $sheet->setCellValue('E1', 'Tanggal Stok');
    $sheet->setCellValue('F1', 'Jumlah Stok');

    $sheet->getStyle('A1:F1')->getFont()->setBold(true);

    $no = 1;
    $baris = 2;
    foreach ($stok as $value) {
        $sheet->setCellValue('A' . $baris, $no++);
        $sheet->setCellValue('B' . $baris, $value->barang && $value->barang->supplier ? $value->barang->supplier->supplier_nama : '-');
        $sheet->setCellValue('C' . $baris, $value->barang ? $value->barang->barang_nama : '-');
        $sheet->setCellValue('D' . $baris, $value->user ? $value->user->nama : '-');
        $sheet->setCellValue('E' . $baris, $value->stok_tanggal);
        $sheet->setCellValue('F' . $baris, $value->stok_jumlah);
        $baris++;
    }

    foreach (range('A', 'F') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    $sheet->setTitle('Data Stok');

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $filename = 'Data Stok ' . date('Y-m-d_H-i-s') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    header('Cache-Control: cache, must-revalidate');
    header('Pragma: public');

    $writer->save('php://output');
    exit;
}

public function export_pdf()
{
    $stok = StokModel::select('barang_id', 'user_id', 'stok_tanggal', 'stok_jumlah')
        ->orderBy('stok_tanggal')
        ->with(['barang.supplier', 'user'])
        ->get();

    $pdf = Pdf::loadView('stok.export_pdf', ['stok' => $stok]);
    $pdf->setPaper('a4', 'portrait');
    $pdf->setOption("isRemoteEnabled", true);
    $pdf->render();

    return $pdf->stream('Data Stok ' . date('Y-m-d H:i:s') . '.pdf');
}
}