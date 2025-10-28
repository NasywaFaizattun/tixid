<?php

namespace App\Http\Controllers;

use App\Models\cr;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{

    public function register(Request $request)
    {
        // Request $request digunakan untuk menangkap data yang dikirim dari FE atau untuk mengambil data dari request/input
        // dd(): debugging, untuk mengecek data sebelum diproses
        // dd($request->all());
        // validasi data
        $request->validate([
            // 'name_input' => 'validasi'
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            // email:dns memastikan email valid
            'email' => 'required|email:dns',
            'password' => 'required'
         ],
         [
        // custom pesan
        // format: 'name_input.validasi' => 'pesan error'
        'first_name.required' => 'Nama depan wajib diisi',
        'first_name.min' => 'Nama depan diisi minimal 3 karakter',
        'last_name.required' => 'Nama belakang wajib diisi',
        'last_name.min' => 'Nama belakang diisi minimal 3 karakter',
        'email.required' => 'Email wajib diisi',
        'email.email' => 'Email diisi dengan data valid',
        'password.required' => 'Password wajib diisi'
         ]);

        //  eloquent (fungsi model) tambah data baru ; create ([])
        $createData = User::create([
            // 'column' => $request->name_input
            'name' => $request->first_name . " " . $request->last_name,
            'email' => $request->email,
            // enkiprsi data: merubah menjadi karakter acak, tidak ada yang bisa tau isi datanya : Hash::make()
            'password' => Hash::make($request->password),
            // role diisi langsung sebagai user agar tidak bisa menjadi admin/staff bagi pendaftar akun
            'role' => 'user'
        ]);

        if($createData) {
            // redirect() perpindahan halaman, route() nama route yang akan dipanggil
            // with() mengirim data session, biasanya untuk notif
            return redirect()->route('login')->with('success', 'Berhasil membuat  akun. Silahkan login');
        } else {
            return redirect()->back()->with('error', 'Gagal! silahkan coba lagi.');
        }
    }

    public function loginAuth(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ], [
            'email.required' => 'Email harus diisi',
            'password.required' => 'Password harus diisi',
        ]);
        // menyimpan data yang akan di verivikasi
    $data = $request->only(['email', 'password']);
    // Auth::attempt() -> verivikasi kecocokan email-pw atau username-pw
    if (Auth::attempt($data)) {
        // setelah berhasil login, dicek lgi terkait rolenya untuk menentukan perpindahan halaman
        if (Auth::user()->role == 'admin'){
            return redirect()->route('admin.dashboard')->with('success', 'Berhasil login!');
        } elseif (Auth::user()->role == 'staff'){
            return redirect()->route('staff.dashboard')->with('success', 'Berhasil login!');
        } else {
            return redirect()->route('home')->with('success', 'Berhasil login!');
        }
    } else {
        return redirect()->back()->with('error', 'Gagal pastikan email dan password sesuai');
    }
    }

    public function logout()
    {
        // Auth::logout(): hapus sesi log in
        Auth::logout();
        return redirect()->route('home')->with('logout', 'Anda sudah logout! silahkan login kembali untuk akses lengkap');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::whereIn('role', ['admin', 'staff'])->get();
        return view('admin.staff.index', compact('users'));
    }

    public function datatables()
    {
        $users = User::whereIn('role', ['admin', 'staff'])->get();
        return DataTables::of($users)
        ->addIndexColumn()
        ->addColumn('role', function($user) {
            if ($user['role']== 'admin') {
                return '<span class="badge badge-primary">Admin</span>';
            } elseif ($user['role']== 'staff')  {
                return '<span class="badge badge-success">staff</span>';
            }
        })
        ->addColumn('btnActions', function($user){
            $btnEdit = '<a href="'. route('admin.users.edit', $user['id']) .'" class="btn btn-info me-2">Edit</a>';
            $btnDelete = '<form action="'. route('admin.users.delete', $user['id']) .'" method="POST">'.
                            csrf_field() .
                            method_field("DELETE") .'
                            <button type="submit" class="btn btn-danger me-2">Hapus</button>
                        </form>';

            return '<div class="d-flex">' . $btnEdit . $btnDelete .'</div>';
        })
        ->rawColumns(['role', 'btnActions'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.staff.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email:dns',
            'password' => 'required'
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'name.min' => 'Nama lengkap diisi minimal 3 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email diisi dengan data valid',
            'password.required' => 'Password wajib diisi'
        ]);
        $createData = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff'
        ]);
        if ($createData) {
            return redirect()->route('admin.users.index')->with('success', 'Berhasil tambah data staff!');
        } else {
            return redirect()->back()->with('error', 'Gagal! silahkan coba lagi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $users = User::find($id);
        return view('admin.staff.edit', compact('users'));

    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|min:3',
        'email' => 'required|email:dns'
    ], [
        'name.required' => 'Nama lengkap wajib diisi',
        'name.min' => 'Nama lengkap diisi minimal 3 karakter',
        'email.required' => 'Email wajib diisi',
        'email.email' => 'Email diisi dengan data valid'
    ]);

    $data = [
        'name'  => $request->name,
        'email' => $request->email,
    ];

    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    $updateData = User::where('id', $id)->update($data);

    if ($updateData) {
        return redirect()->route('admin.users.index')->with('success', 'Berhasil mengubah data!');
    } else {
        return redirect()->back()->with('error', 'Gagal! silahkan coba lagi');
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $user = User::find($id);
        $user->forceDelete();

        return redirect()->route('admin.users.index')->with('success', 'Berhasil hapus data!');
    }

    public function exportExcel()
    {
        // nama file yang akan terunduh
        $fileName = 'data-petugas.xlsx';
        // proses download
        return Excel::download(new UserExport, $fileName);
    }

    public function trash()
    {
    $userTrash = User::onlyTrashed()->get();
    return view('admin.staff.trash', compact('userTrash'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->find($id);
        $user->restore();
        return redirect()->route('admin.users.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $user = User::onlyTrashed()->find($id);
        $user->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus seutuhnya!');
    }
}
