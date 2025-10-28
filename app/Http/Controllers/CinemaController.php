<?php

namespace App\Http\Controllers;

use App\Models\Cinema;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CinemaExport;
use Yajra\DataTables\Facades\DataTables;

class CinemaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cinemas = Cinema::all();
        // Cinema::all() -> mengambil semua data pada model Cinema (tabel cinemas)
        // mengirim data dr controller ke blade -> compact
        // isi compact sama dengan nama variable
        return view('admin.cinema.index', compact('cinemas'));
    }

    public function datatables()
    {

        $cinemas = Cinema::query();
        return DataTables::of($cinemas)
        ->addIndexColumn()
        ->addColumn('btnActions', function($cinema){
            $btnEdit = '<a href="'. route('admin.cinemas.edit', $cinema['id']) .'" class="btn btn-info me-2">Edit</a>';
            $btnDelete = '<form action="'. route('admin.cinemas.delete', $cinema['id']) .'" method="POST">'.
                            csrf_field() .
                            method_field("DELETE") .'
                            <button type="submit" class="btn btn-danger me-2">Hapus</button>
                        </form>';

            return '<div class="d-flex">' . $btnEdit . $btnDelete .'</div>';
        })
        ->rawColumns(['btnActions'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.cinema.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'required|min:10'
        ], [
            'name.required' => 'Nama bioskop harus diisi',
            'location.required' => 'Lokasi bioskop harus diisi',
            'location.min' => 'Lokasi bioskop harus diisi minimal 10 karakter'
        ]);
        $createData = Cinema::create([
            'name' => $request->name,
            'location' => $request->location,
        ]);
        if ($createData) {
            return redirect()->route('admin.cinemas.index')->with('success', 'Berhasil tambah data bioskop!');
        } else {
            return redirect()->back()->with('error', 'Gagal! silahkan coba lagi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cinema $cinema)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // edit($id) -> $id di ambil dari route {id}
        $cinema = Cinema::find($id);
        // find() mencari berdasarkan id
        return view('admin.cinema.edit', compact('cinema'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'location' => 'required|min:10'
        ], [
            'name.required' => 'Nama Bioskop harus diisi',
            'location.required' => 'Lokasi Bioskop harus diisi',
            'location.min' => 'Lokasi bioskop harus diisi minimal 10 karakter'
        ]);
        // where() -> mencari data. format: where('nama_column', 'value')
        // sebelum update() wajib ada where() untuk mencari data yang akan diupdatenya
        $updateData = Cinema::where('id', $id)->update([
            'name' => $request->name,
            'location' => $request->location
        ]);
        if ($updateData) {
            return redirect()->route('admin.cinemas.index')->with('success', 'Berhasil mengubah data!');
        } else {
            return redirect()->back()->with('error', 'Gagal! silahkan coba lagi');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // count() : menghitung jumlah data yang didapat
        $schedules = Schedule::where('cinema_id', $id)->count();
        if ($schedules) {
            return redirect()->route('admin.cinemas.index')->with('error', 'Tidak dapat menghapus data bioskop! Data tertaut dengan jadwal tayang');
        }
        // sblm dihapus, dicari dulu datanya
        Cinema::where('id', $id)->delete();
        return redirect()->route('admin.cinemas.index')->with('success', 'Berhasil hapus data!');
    }

    public function exportExcel()
    {
        // nama file yang akan terunduh
        $fileName = 'data-Cinema.xlsx';
        // proses download
        return Excel::download(new CinemaExport, $fileName);
    }

    public function trash()
    {
    $cinemaTrash = Cinema::onlyTrashed()->get();
    return view('admin.cinema.trash', compact('cinemaTrash'));
    }

    public function restore($id)
    {
        $cinema = Cinema::onlyTrashed()->find($id);
        $cinema->restore();
        return redirect()->route('admin.cinemas.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $cinema = Cinema::onlyTrashed()->find($id);
        $cinema->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus seutuhnya!');
    }
}
