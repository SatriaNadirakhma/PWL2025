<?php

namespace App\Http\Controllers;

use App\Models\PenjualanModel;
use App\Models\UserModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Data Penjualan',
            'list' => ['Home', 'Penjualan']
        ];

        $page = (object) [
            'title' => 'Penjualan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'penjualan';

        return view('penjualan.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $penjualans = PenjualanModel::with('user');
            
        return DataTables::of($penjualans)
            ->addIndexColumn()
            ->addColumn('user.nama', function ($penjualan) {
                return $penjualan->user->nama ?? '-';
            })
            ->addColumn('aksi', function ($penjualan) { 
                $btn = '<button onclick="modalAction(\''.url('/penjualan/' . $penjualan->penjualan_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        $users = UserModel::all(['user_id', 'nama']);
        return view('penjualan.create_ajax', compact('users'));
    }

    public function store_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pembeli' => 'required|string|min:3|max:100',
            'penjualan_tanggal' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $lastId = PenjualanModel::max('penjualan_id') ?? 0;
            $kode = 'PJN' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

            PenjualanModel::create([
                'penjualan_kode' => $kode,
                'nama_pembeli' => $request->nama_pembeli,
                'penjualan_tanggal' => $request->penjualan_tanggal,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show_ajax($id)
    {
        $penjualan = PenjualanModel::with(['user', 'detail.barang'])->findOrFail($id);
        return view('penjualan.show_ajax', compact('penjualan'));
    }
}