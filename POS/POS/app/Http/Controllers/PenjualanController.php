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

        return view('penjualan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }
    public function list(Request $request)
    {
        $penjualans = PenjualanModel::select('penjualan_id', 'penjualan_kode', 'pembeli', 'penjualan_tanggal', 'user_id')
            ->with('user');
            
        return DataTables::of($penjualans)
            ->addIndexColumn()
            ->addColumn('aksi', function ($penjualan) { 
                $btn =  '<button onclick="modalAction(\''.url('/penjualan/' . $penjualan->penjualan_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function create_ajax()
    {
        $user = UserModel::select('user_id', 'nama')->get();

        return view('penjualan.create_ajax')
            ->with('user', $user);
    }
    public function store_ajax(Request $request)
    {
        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'pembeli'           => 'required|min:3',
                'penjualan_tanggal' => 'required|date',
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

            $lastId = PenjualanModel::max('penjualan_id') ?? 0;
            $kode = 'PJN' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

            $tanggal = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->penjualan_tanggal)
                    ->format('Y-m-d H:i:s');

            PenjualanModel::create([
                'penjualan_kode'    => $kode,
                'pembeli'           => $request->pembeli,
                'penjualan_tanggal' => $tanggal,
                'user_id'           => $user->user_id,
            ]);
            
            return response()->json([
                'status' => true,
                'message' => 'Data penjualan berhasil disimpan'
            ]);
        }
        redirect('/');
    }
    public function show_ajax(string $id)
    {
        $penjualan = PenjualanModel::select('penjualan_id', 'penjualan_kode', 'pembeli', 'penjualan_tanggal', 'user_id')
            ->with('detail.barang')
            ->with('user')
            ->find($id);

        return view('penjualan.show_ajax', ['penjualan' => $penjualan]);
    }
}