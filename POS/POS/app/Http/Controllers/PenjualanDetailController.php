<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\PenjualanDetailModel;
use App\Models\PenjualanModel;
use App\Models\StokModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

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
        $penjualans = PenjualanModel::all(['penjualan_id', 'penjualan_kode']);

        return view('detailPenjualan.index', compact('breadcrumb', 'page', 'penjualans', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $details = PenjualanDetailModel::with(['penjualan', 'barang']);

        if ($request->has('filter_penjualan') && $request->filter_penjualan != '') {
            $details->where('penjualan_id', $request->filter_penjualan);
        }
            
        return DataTables::of($details)
            ->addIndexColumn()
            ->addColumn('penjualan.penjualan_kode', function ($detail) {
                return $detail->penjualan->penjualan_kode ?? '-';
            })
            ->addColumn('barang.barang_nama', function ($detail) {
                return $detail->barang->barang_nama ?? '-';
            })
            ->addColumn('subtotal', function ($detail) {
                return $detail->harga * $detail->jumlah;
            })
            ->addColumn('aksi', function ($detail) {
                $btn = '<button onclick="modalAction(\''.url('/detailPenjualan/'.$detail->detail_id.'/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/detailPenjualan/'.$detail->detail_id.'/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\''.url('/detailPenjualan/'.$detail->detail_id.'/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        $barangs = BarangModel::all(['barang_id', 'barang_nama', 'harga_jual']);
        $penjualans = PenjualanModel::all(['penjualan_id', 'penjualan_kode']);

        return view('detailPenjualan.create_ajax', compact('barangs', 'penjualans'));
    }

    public function store_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'penjualan_id' => 'required|integer|exists:t_penjualan,penjualan_id',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|integer|exists:m_barang,barang_id',
            'items.*.harga' => 'required|integer|min:1',
            'items.*.jumlah' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            \DB::beginTransaction();

            $savedItems = [];
            $errors = [];

            foreach ($request->items as $item) {
                $barangId = $item['barang_id'];
                $jumlah = $item['jumlah'];

                $stok = StokModel::where('barang_id', $barangId)->first();

                if (!$stok) {
                    $errors[] = "Stok untuk barang ID $barangId tidak ditemukan";
                    continue;
                }

                if ($stok->stok_jumlah < $jumlah) {
                    $errors[] = "Stok tidak cukup untuk barang ID $barangId. Tersedia: {$stok->stok_jumlah}, diminta: $jumlah";
                    continue;
                }

                // Update stok
                $stok->decrement('stok_jumlah', $jumlah);
                $stok->update([
                    'stok_tanggal' => now(),
                    'user_id' => auth()->id()
                ]);

                // Simpan detail penjualan
                $savedItems[] = PenjualanDetailModel::create([
                    'penjualan_id' => $request->penjualan_id,
                    'barang_id' => $barangId,
                    'harga' => $item['harga'],
                    'jumlah' => $jumlah
                ]);
            }

            if (!empty($errors)) {
                \DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Beberapa item gagal diproses',
                    'errors' => $errors
                ], 422);
            }

            \DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data penjualan dan stok berhasil diperbarui',
                'data' => $savedItems
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show_ajax($id)
    {
        $detail = PenjualanDetailModel::with(['penjualan', 'barang'])->findOrFail($id);
        return view('detailPenjualan.show_ajax', compact('detail'));
    }

    public function edit_ajax($id)
    {
        $detail = PenjualanDetailModel::findOrFail($id);
        $barangs = BarangModel::all(['barang_id', 'barang_nama', 'harga_jual']);
        $penjualans = PenjualanModel::all(['penjualan_id', 'penjualan_kode']);

        return view('detailPenjualan.edit_ajax', compact('detail', 'barangs', 'penjualans'));
    }

    public function update_ajax(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'penjualan_id' => 'required|integer|exists:t_penjualan,penjualan_id',
            'barang_id' => 'required|integer|exists:m_barang,barang_id',
            'harga' => 'required|integer|min:1',
            'jumlah' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            \DB::beginTransaction();

            $detail = PenjualanDetailModel::findOrFail($id);
            $oldBarangId = $detail->barang_id;
            $oldJumlah = $detail->jumlah;
            $newJumlah = $request->jumlah;

            // Kembalikan stok lama
            if ($oldBarangId == $request->barang_id) {
                $stok = StokModel::where('barang_id', $oldBarangId)->first();
                $stok->increment('stok_jumlah', $oldJumlah);
                
                // Kurangi stok baru
                $stok->decrement('stok_jumlah', $newJumlah);
            } else {
                // Kembalikan stok barang lama
                $oldStok = StokModel::where('barang_id', $oldBarangId)->first();
                $oldStok->increment('stok_jumlah', $oldJumlah);
                
                // Kurangi stok barang baru
                $newStok = StokModel::where('barang_id', $request->barang_id)->first();
                if ($newStok->stok_jumlah < $newJumlah) {
                    throw new \Exception("Stok tidak cukup untuk barang ID {$request->barang_id}");
                }
                $newStok->decrement('stok_jumlah', $newJumlah);
            }

            // Update detail penjualan
            $detail->update([
                'penjualan_id' => $request->penjualan_id,
                'barang_id' => $request->barang_id,
                'harga' => $request->harga,
                'jumlah' => $request->jumlah
            ]);

            \DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data detail penjualan berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete_ajax($id)
    {
        try {
            \DB::beginTransaction();

            $detail = PenjualanDetailModel::findOrFail($id);
            
            // Kembalikan stok
            $stok = StokModel::where('barang_id', $detail->barang_id)->first();
            $stok->increment('stok_jumlah', $detail->jumlah);
            
            // Hapus detail penjualan
            $detail->delete();

            \DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data detail penjualan berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}