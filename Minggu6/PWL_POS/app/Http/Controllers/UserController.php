<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    // Menampilkan halaman awal user
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list'  => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; // set menu yang sedang aktif

        $level = LevelModel::all();

        return view('user.index', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'level'      => $level,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data user dalam bentuk json untuk datatables 
    // public function list(Request $request)
    // {
    //     $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
    //         ->with('level');

    //     // Filter data user berdasarkan level_id
    //     if ($request->level_id) {
    //         $users->where('level_id', $request->level_id);
    //     }
            
    //     return DataTables::of($users)
    //         // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
    //         ->addIndexColumn()
    //         ->addColumn('aksi', function ($user) { // menambahkan kolom aksi
    //             $btn = '<a href="' . url('/user/' . $user->user_id) . '" class="btn btn-info btn-sm">Detail</a> ';
    //             $btn .= '<a href="' . url('/user/' . $user->user_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
    //             $btn .= '<form class="d-inline-block" method="POST" action="' .
    //                 url('/user/' . $user->user_id) . '">'
    //                 . csrf_field() . method_field('DELETE') .
    //                 '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
    //             return $btn;
    //         })
    //         ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
    //         ->make(true);
    // }

    // Ambil data user dalam bentuk json untuk datatables 
    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
            ->with('level');
    
    // Filter data user berdasarkan level_id 
    if ($request->level_id){
        $users->where('level_id',$request->level_id);
    }

    
    return DataTables::of($users)
        ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
        ->addColumn('aksi', function ($user) { // menambahkan kolom aksi
        /* $btn = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btn- sm">Detail</a> ';
        $btn .= '<a href="'.url('/user/' . $user->user_id . '/edit').'" class="btn btn- warning btn-sm">Edit</a> ';
        $btn .= '<form class="d-inline-block" method="POST" action="'. url('/user/'.$user-
        >user_id).'">'
        . csrf_field() . method_field('DELETE') .
        '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakit menghapus data ini?\');">Hapus</button></form>';*/
        $btn = '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';
        $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';
        $btn .= '<button onclick="modalAction(\''.url('/user/' . $user->user_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';
            return $btn;    
        })
        ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
        ->make(true);
}

    

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah user baru'
        ];

        $level = LevelModel::all(); // Ambil data level untuk ditampilkan di form
        $activeMenu = 'user'; // Set menu yang sedang aktif

        return view('user.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username', // username unik dan minimal 3 karakter
            'nama' => 'required|string|max:100', // nama wajib diisi, max 100 karakter
            'password' => 'required|min:5', // password minimal 5 karakter
            'level_id' => 'required|integer', // level_id harus angka
        ]);

        // Menyimpan data ke database
        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password), // Enkripsi password
            'level_id' => $request->level_id,
        ]);

        // Redirect dengan pesan sukses
        return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }

    public function show(string $id)
    {
        $user = UserModel::with('level')->find($id);

        if (!$user) {
            return redirect('/user')->with('error', 'User tidak ditemukan');
        }

        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list' => ['Home', 'User', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail User'
        ];

        // Menandai menu yang aktif
        $activeMenu = 'user';

        return view('user.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'activeMenu' => $activeMenu
        ]);
    }

    public function edit(string $id)
    {
        // Mengambil data user berdasarkan ID
        $user = UserModel::find($id);
        // Mengambil semua data level
        $level = LevelModel::all();

        // Jika user tidak ditemukan, redirect ke halaman user dengan pesan error
        if (!$user) {
            return redirect('/user')->with('error', 'User tidak ditemukan');
        }

        // Data breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list' => ['Home', 'User', 'Edit']
        ];

        // Data halaman
        $page = (object) [
            'title' => 'Edit User'
        ];

        // Menandai menu yang aktif
        $activeMenu = 'user';

        // Mengembalikan view dengan data yang diperlukan
        return view('user.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'user' => $user,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan perubahan data user
    public function update(Request $request, string $id)
    {
        // Validasi input
        $request->validate([
            'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
            'nama' => 'required|string|max:100',
            'password' => 'nullable|min:5', // Password bisa dikosongkan atau minimal 5 karakter
            'level_id' => 'required|integer'
        ]);

        // Mengambil data user berdasarkan ID
        $user = UserModel::find($id);
        if (!$user) {
            return redirect('/user')->with('error', 'User tidak ditemukan');
        }

        // Update data user
        $user->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
            'level_id' => $request->level_id,
        ]);

        // Redirect ke halaman user dengan pesan sukses
        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }

    public function destroy(string $id)
    {
    $check = UserModel::find($id);
    if (!$check) { // untuk mengecek apakah data user dengan id yang dimaksud ada atau tidak
        return redirect('/user')->with('error', 'Data user tidak ditemukan');
    }

    try {
        UserModel::destroy($id); // Hapus data user
        return redirect('/user')->with('success', 'Data user berhasil dihapus');
    } catch (\Illuminate\Database\QueryException $e) {
        // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
        return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
    }
    }

    public function create_ajax()
    {
        $level = LevelModel::select('level_id', 'level_nama')->get();

        return view('user.create_ajax')
            ->with('level', $level);
    }

    public function store_ajax(Request $request)
    {
        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:m_user,username',
                'nama'     => 'required|string|max:100',
                'password' => 'required|min:5',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            UserModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan'
            ]);
        }
        redirect('/');
    }

    public function edit_ajax(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::select('level_id', 'level_nama')->get();
        return view('user.edit_ajax', ['user' => $user, 'level' => $level]);
    }

    public function update_ajax(Request $request, $id)
{
    // Cek apakah request berasal dari AJAX atau mengharapkan JSON
    if ($request->ajax() || $request->wantsJson()) {
        
        $rules = [
            'level_id'  => 'required|integer',
            'username'  => 'required|max:20|unique:m_user,username,' . $id . ',user_id',
            'nama'      => 'required|max:100',
            'password'  => 'nullable|min:6|max:20'
        ];
        
        // Validasi input
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'status'   => false, // Respon JSON, true: berhasil, false: gagal
                'message'  => 'Validasi gagal.',
                'msgField' => $validator->errors() // Menunjukkan field yang error
            ]);
        }
        
        // Cek apakah user dengan ID yang diberikan ada
        $user = UserModel::find($id);
        if ($user) {
            // Jika password tidak diisi, hapus dari request
            if (!$request->filled('password')) {
                $request->request->remove('password');
            }
            
            $user->update($request->all());
            
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
        $user = UserModel::find($id);

        return view('user.confirm_ajax', ['user' => $user]);
    }

    public function delete_ajax(Request $request, $id)
    {
    // Cek apakah request dari AJAX atau JSON
    if ($request->ajax() || $request->wantsJson()) {
        $user = UserModel::find($id); // Mencari user berdasarkan ID

        if ($user) {
            $user->delete(); // Menghapus user jika ditemukan
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

    // Redirect jika bukan permintaan AJAX
    return redirect('/');
}

}
