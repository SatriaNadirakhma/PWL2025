<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\PenjualanDetailModel;
use App\Models\PenjualanModel;
use App\Models\StokModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PenjualanDetailController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Detail Penjualan',
            'list' => ['Home', 'Detail Penjualan']
        ];

        $page = (object) [
            'title' => 'Detail Penjualan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'detailPenjualan';

        $penjualan = PenjualanModel::select('penjualan_id', 'penjualan_kode')->get();

        return view('detailPenjualan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'penjualan' => $penjualan, 'activeMenu' => $activeMenu]);
    }
    public function list(Request $request)
    {
        $details = PenjualanDetailModel::select('detail_id', 'harga', 'jumlah', 'barang_id', 'penjualan_id')
            ->with('penjualan')
            ->with('barang');

        $penjualan_id = $request->input('filter_penjualan');
        if (!empty($penjualan_id)) {
            $details->where('penjualan_id', $penjualan_id);
        }
            
        return DataTables::of($details)
            ->addIndexColumn()
            ->addColumn('aksi', function ($detail) {
                $btn =  '<button onclick="modalAction(\''.url('/detailPenjualan/' . $detail->detail_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/detailPenjualan/' . $detail->detail_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/detailPenjualan/' . $detail->detail_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function create_ajax()
    {
        $barang = BarangModel::select('barang_id', 'barang_nama', 'harga_jual')->get();
        $penjualan = PenjualanModel::select('penjualan_id', 'penjualan_kode')->get();

        return view('detailPenjualan.create_ajax')
            ->with('barang', $barang)
            ->with('penjualan', $penjualan);
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'penjualan_id' => 'required|integer',
                'items' => 'required|array|min:1',
                'items.*.barang_id' => 'required|integer',
                'items.*.harga' => 'required|integer',
                'items.*.jumlah' => 'required|integer'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $savedItems = [];

            foreach ($request->items as $item) {
                $barangId = $item['barang_id'];
                $jumlah = $item['jumlah'];

                // Ambil stok berdasarkan barang_id
                $stok = StokModel::where('barang_id', $barangId)->first();

                if (!$stok) {
                    return response()->json([
                        'status' => false,
                        'message' => "Stok untuk barang ID $barangId tidak ditemukan."
                    ]);
                }

                // Cek apakah stok cukup
                if ($stok->stok_jumlah < $jumlah) {
                    return response()->json([
                        'status' => false,
                        'message' => "Stok tidak cukup untuk barang ID $barangId. Tersedia: {$stok->stok_jumlah}, diminta: $jumlah."
                    ]);
                }

                // Update stok dengan mengurangi jumlah
                $stok->stok_jumlah -= $jumlah;
                $stok->stok_tanggal = now(); // update tanggal terakhir stok diubah (optional)
                $stok->user_id = auth()->user()->user_id; // update user terakhir ubah stok (optional)
                $stok->save();

                // Simpan detail penjualan
                $savedItems[] = PenjualanDetailModel::create([
                    'penjualan_id' => $request->penjualan_id,
                    'barang_id' => $barangId,
                    'harga' => $item['harga'],
                    'jumlah' => $jumlah
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data penjualan dan stok berhasil diperbarui',
                'data' => $savedItems
            ]);
        }

        return redirect('/');
    }        
}