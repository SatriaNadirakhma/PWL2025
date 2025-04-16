<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\StokModel;
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
        $barang = BarangModel::all(['barang_id', 'barang_nama']);

        return view('stok.index', compact('breadcrumb', 'page', 'barang', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $stoks = StokModel::with(['barang', 'user']);

        if ($request->has('filter_stok') && $request->filter_stok != '') {
            $stoks->where('barang_id', $request->filter_stok);
        }

        return DataTables::of($stoks)
            ->addIndexColumn()
            ->addColumn('user.nama', function ($stok) {
                return $stok->user->nama ?? '-';
            })
            ->addColumn('barang.barang_nama', function ($stok) {
                return $stok->barang->barang_nama ?? '-';
            })
            ->addColumn('aksi', function ($stok) {
                $btn = '<button onclick="modalAction(\''.url('/stok/'.$stok->stok_id.'/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/stok/'.$stok->stok_id.'/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/stok/'.$stok->stok_id.'/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        $barang = BarangModel::all(['barang_id', 'barang_nama']);
        $user = UserModel::all(['user_id', 'nama']);

        return view('stok.create_ajax', compact('barang', 'user'));
    }

    public function store_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barang_id' => 'required|integer|exists:m_barang,barang_id',
            'stok_tanggal' => 'required|date',
            'stok_jumlah' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            StokModel::create([
                'barang_id' => $request->barang_id,
                'user_id' => auth()->id(),
                'stok_tanggal' => $request->stok_tanggal,
                'stok_jumlah' => $request->stok_jumlah
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data stok berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data: '.$e->getMessage()
            ], 500);
        }
    }

    public function show_ajax($id)
    {
        $stok = StokModel::with(['barang', 'user'])->findOrFail($id);
        return view('stok.show_ajax', compact('stok'));
    }

    public function edit_ajax($id)
    {
        $stok = StokModel::findOrFail($id);
        $barang = BarangModel::all(['barang_id', 'barang_nama']);
        $user = UserModel::all(['user_id', 'nama']);

        return view('stok.edit_ajax', compact('stok', 'barang', 'user'));
    }

    public function update_ajax(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'barang_id' => 'required|integer|exists:m_barang,barang_id',
            'stok_tanggal' => 'required|date',
            'stok_jumlah' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $stok = StokModel::findOrFail($id);
            $stok->update([
                'barang_id' => $request->barang_id,
                'stok_tanggal' => $request->stok_tanggal,
                'stok_jumlah' => $request->stok_jumlah
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data stok berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui data: '.$e->getMessage()
            ], 500);
        }
    }

    public function delete_ajax($id)
    {
        try {
            $stok = StokModel::findOrFail($id);
            $stok->delete();

            return response()->json([
                'status' => true,
                'message' => 'Data stok berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus data: '.$e->getMessage()
            ], 500);
        }
    }

    public function import()
    {
        return view('stok.import');
    }

    public function import_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_stok' => 'required|mimes:xlsx|max:1024'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('file_stok');
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            $data = [];
            foreach (array_slice($rows, 1) as $row) {
                $data[] = [
                    'barang_id' => $row[0],
                    'user_id' => auth()->id(),
                    'stok_tanggal' => now(),
                    'stok_jumlah' => $row[1],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            StokModel::insert($data);

            return response()->json([
                'status' => true,
                'message' => 'Data stok berhasil diimport'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengimport data: '.$e->getMessage()
            ], 500);
        }
    }

    public function export_excel()
    {
        $stoks = StokModel::with(['barang', 'user'])->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Barang');
        $sheet->setCellValue('C1', 'Petugas');
        $sheet->setCellValue('D1', 'Tanggal Stok');
        $sheet->setCellValue('E1', 'Jumlah Stok');

        // Data
        $row = 2;
        foreach ($stoks as $index => $stok) {
            $sheet->setCellValue('A'.$row, $index+1);
            $sheet->setCellValue('B'.$row, $stok->barang->barang_nama ?? '-');
            $sheet->setCellValue('C'.$row, $stok->user->nama ?? '-');
            $sheet->setCellValue('D'.$row, $stok->stok_tanggal);
            $sheet->setCellValue('E'.$row, $stok->stok_jumlah);
            $row++;
        }

        // Formatting
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'data_stok_'.date('Ymd_His').'.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $stoks = StokModel::with(['barang', 'user'])->get();
        $pdf = Pdf::loadView('stok.export_pdf', compact('stoks'));
        return $pdf->stream('data_stok_'.date('Ymd_His').'.pdf');
    }
}