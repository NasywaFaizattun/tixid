<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Cinema;
use App\Models\Movie;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ScheduleExport;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cinemas = Cinema::all();
        $movies = Movie::all();

        // karena cinema_id dan movie_id di db hanya berupa angka, untuk mengambil detail relasi gunakan eloquent with()
        // with() : mengambil detail data relasi, di ambil dari nama fungsi relasi di model
        $schedules = Schedule::with(['cinema', 'movie'])->get();

        return view('staff.schedule.index', compact('cinemas', 'movies', 'schedules'));
    }

    public function datatables()
    {

        $schedules = Schedule::with(['cinema', 'movie'])->get();
        return DataTables::of($schedules)
        ->addIndexColumn()
        ->addColumn('cinema_id', function($schedule) {
            return $schedule->cinema ? $schedule->cinema->name : '-';
        })
        ->addColumn('movie_id', function($schedule) {
            return $schedule->movie ? $schedule->movie->title : '-';
        })
        ->addColumn('price', function($schedule) {
            return 'Rp. ' . number_format($schedule['price'], 0, ',', '.');
        })
        ->addColumn('hours', function($schedule) {
            if (is_array($schedule->hours)) {
                $list = '<ul class="mb-0">';
                foreach ($schedule->hours as $hour) {
                    $list .= "<li>{$hour}</li>";
                }
                $list .= "</ul>";
                return $list;
            }
            return $schedule->hours;
        })
        ->addColumn('btnActions', function($schedule){
            $btnEdit = '<a href="'. route('staff.schedules.edit', $schedule['id']) .'" class="btn btn-info me-2">Edit</a>';
            $btnDelete = '<form action="'. route('staff.schedules.delete', $schedule['id']) .'" method="POST">'.
                            csrf_field() .
                            method_field("DELETE") .'
                            <button type="submit" class="btn btn-danger me-2">Hapus</button>
                        </form>';

            return '<div class="d-flex">' . $btnEdit . $btnDelete .'</div>';
        })
        ->rawColumns(['cinema_id', 'movie_id', 'price', 'hours', 'btnActions'])
        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cinema_id' => 'required',
            'movie_id' => 'required',
            'price' => 'required|numeric',
            // karna hours array, yg di validasi isi array nya (tanda.) dan di validasi semua isi item array (tanda *)
            'hours.*' => 'required|date_format:H:i'
        ], [
            'cinema_id.required' => "Bioskop harus dipilih",
            'movie_id.required' => "Film harus dipilih",
            'price.required' => "Harga harus diisi",
            'price.numeric' => 'Harga harus diisi dengan angka',
            'hours.*.required' => "Jam tayang harus diisi minimal 1 data",
            'hours.*.date_format' => "Jam tayang harus diisi dengan jam:menit",
        ]);

        // pengecekan apakah ada bioskop dan film yg di pilih sekarang di dbnya. Kalau ada ambil jamnya aja
        $hours = Schedule::where('cinema_id', $request->cinema_id)->where('movie_id', $request->movie_id)->value('hours');
        // jika sudah ada data dengan bioskop dan fil yang sama maka ambl data jam tsb, jika tidk buat array kosong
        $hoursBefore = $hours ?? [];
        // gabungan array jam sebelumnya dengan array jam yang baru ditambahin
        $mergeHours = array_merge($hoursBefore, $request->hours);
        // jika ada jam yang di duplikat, ambil salah satu
        // gunakan data ini untuk disimpan di db
        $newHours = array_unique($mergeHours);

        // updateOrCreate : mengubah jika sudah ada, menambahkan jika blm ada
        $createData = Schedule::updateOrCreate([
            // array pertama, acuan pencarian data
            'cinema_id' => $request->cinema_id,
            'movie_id' => $request->movie_id,
        ], [
            // array kedua, data yang akan di update
            'price' => $request->price,
            'hours' => $newHours,
        ]);
        if ($createData) {
            return redirect()->route('staff.schedules.index')->with('success', 'Berhasil menambahkan data!');
        } else {
            return redirect()->route('staff.schedules.index')->with('error', 'Gagal! coba lagi.');
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule,$id)
    {
        $schedule = Schedule::where('id', $id)->with(['cinema', 'movie'])->first();
        return view('staff.schedule.edit', compact('schedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule, $id)
    {
        $request->validate([
            'price' => 'required|numeric',
            'hours.*' => 'required|date_format:H:i'
        ], [
            'price.required' => 'Harga harus diisi',
            'price.numeric' => 'Harga harus diisi dengan angka',
            'hours.*.required' => 'Jam tayang harus diisi',
            'hours.*.date_format' => 'Jam tayang harus diisi dengan format jam:menit',
        ]);

        $updateData = Schedule::where('id', $id)->update([
            'price' => $request->price,
            'hours' => $request->hours
        ]);

        if ($updateData) {
            return redirect()->route('staff.schedules.index')->with('seccess', 'Berhasil mengubah data!');
        } else {
            return redirect()->back()->with('error', 'Gagal coba lagi');
        }

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule, $id)
    {
        Schedule::where('id', $id)->delete();
        return redirect()->route('staff.schedules.index')->with('success', 'Berhasil menghapus data!');
    }

    public function trash()
    {
    $scheduleTrash = Schedule::with(['cinema', 'movie'])->onlyTrashed()->get();
    return view('staff.schedule.trash', compact('scheduleTrash'));
    }

    public function restore($id)
    {
        $schedule = Schedule::onlyTrashed()->find($id);
        $schedule->restore();
        return redirect()->route('staff.schedules.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $schedule = Schedule::onlyTrashed()->find($id);
        $schedule->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus seutuhnya!');
    }

    public function exportExcel()
    {

        // nama file yang akan terunduh
        $fileName = 'data-jadwal-tayang.xlsx';
        // proses download
        return Excel::download(new ScheduleExport, $fileName);
    }

}
