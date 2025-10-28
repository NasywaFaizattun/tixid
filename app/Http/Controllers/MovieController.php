<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MovieExport;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = Movie::all();
        return view('admin.movie.index', compact('movies'));
    }

    public function datatables()
    {
        // jika data yang di ambil tidak ada rekasi gunakan query() untuk mentiapkan, jika ada rekasi gunakan : Movie::with(['schedules'])
        // query : menyiapkan query eloquent model untuk dipakai di datatables
        $movies = Movie::query()->get();
        // of() : mengambil query eloquent dari model yang akan diproses datanya
        return DataTables::of($movies)
        // memunculkan angka 1-dst di table
        ->addIndexColumn()
        // addColumn('', function) : membuat column, menyajikan data selain data asli dari db
        ->addColumn('imgPoster', function($movie){
            $imgUrl = asset('storage/' . $movie['poster']);
            return '<img src="'. $imgUrl .'" width="120">';
        })
        ->addColumn('activeBadge', function($movie) {
            if ($movie['actived'] == 1) {
                return '<span class="badge badge-success">Aktif</span>';
            } else {
                return '<span class="badge badge-danger">Non-Aktif</span>';
            }
        })
        ->addColumn('btnActions', function($movie){
            $btnDetail = '<button class="btn btn-secondary me-2" onclick=\'showModal('. json_encode($movie) .')\'>Detail</button>';

            $btnEdit = '<a href="'. route('admin.movies.edit', $movie['id']) .'" class="btn btn-info me-2">Edit</a>';

            $btnDelete = '<form action="'. route('admin.movies.delete', $movie['id']) .'" method="POST">'.
                            csrf_field() .
                            method_field("DELETE") .'
                            <button type="submit" class="btn btn-danger me-2">Hapus</button>
                        </form>';

            if ($movie['actived'] == 1) {
                $btnNonAktif = '<form action="'. route('admin.movies.nonactive', $movie['id']) .'" method="POST">'.
                            csrf_field() .
                            method_field("PATCH") .'
                            <button type="submit" class="btn btn-primary me-2">Non-Aktif</button>
                        </form>';
            } else {
                $btnNonAktif = '';
            }

            return '<div class="d-flex">' . $btnDetail . $btnEdit . $btnDelete . $btnNonAktif .'</div>';
        })
        // daftarkan nama dari addColumn untuk dipanggil di JS datatables nya
        ->rawColumns(['imgPoster', 'activeBadge', 'btnActions'])
        // ubah query jadi json agar bisa di baca JS datatables
        ->make(true);
    }

    public function home()
    {
        // where() : untuk mencari data. format yang digunakan where('field', 'operator', 'value')
        // get() : mengambil semua data hasil filter
        // first() : mengambil satu data pertama hasil filter
        // paginate() : membagi data menjadi beberapa halaman
        // orderBy() : untuk mengurutkan data. formatnya orderBy('field', 'type')
        // type ASC : mengurutkan dari A-Z atau 0-9 atau terlama ke terbaru
        // type DESC : mengurutkan dari Z-A atau 9-0 atau terbaru ke terlama
        // limit() : mengambil data dengan jumlah tertentu formatnya limit(angka)
        $movies = Movie::where('actived', 1)->orderBy('created_at', 'desc')->limit(4)->get();
        return view('home', compact('movies'));
    }

    public function homeMovies(Request $request)
    {
        // pengambilan data dari input form search
        // name inputnya name="search_movie"
        $nameMovie = $request->search_movie;
        // jika nameMovie (input search diisi, tidak kosong)
        if ($nameMovie != "") {
            // LIKE : mencari data yang mirip/mengandung teks yang diminta
            //  % depan : mencari kata belakang, % belakang : mencari katakata depan, % depan belakang mencari dari kata depan belakang
            $movies = Movie::where('title', 'LIKE', '%'.$nameMovie.'%')->where('actived', 1)->orderBy('created_at', 'DESC')->get();
        } else{
        $movies = Movie::where('actived', 1)->orderBy('created_at', 'desc')->get();
        }
        return view('movies', compact('movies'));
    }

    public function movieSchedule($movie_id, Request $request)
    {
        // mengambil ? bisa dengan Request $request
        $sortirHarga = $request->sortirHarga;

        if ($sortirHarga) {
            // with(['namerelasi' => function($q) {...}]) : melakukan filter di relasi
            $movie = Movie::where('id', $movie_id)->with(['schedules' => function($q) use($sortirHarga) {
                // $q mewakili query yang artinya model schedule
                // karan $sortirHarga ada diluar function($q) jadi import pake use()
                $q->orderBy('price', $sortirHarga);
            }, 'schedules.cinema'])->first();
        } else {
            // karena cinema relasi adanya di schedule, jadi with nya schedule.cinema (pake titik)
            // ambil satu film : first()
            $movie = Movie::where('id', $movie_id)->with(['schedules', 'schedules.cinema'])->first();
        }

        $sortirAlfabet = $request->sortirAlfabet;
        if ($sortirAlfabet == 'ASC' ) {
            // karena alfabet dari name di cinema, cinema di 'schedules.cinema' (cinema relasi kedua) jadi gunakan collection untuk urutannya
            // $movie->schedules : mengambil dari $movue diatas bagian data schedules nya
            $movie->schedules = $movie->schedules->sortBy(function($schedule) {
                // sortBy : mengurutkan collection (hasil pengambilam data) secara ASC
                // diurutkan berdasarkan data di return (data name dari cinema, cinema dari relasi schedule)
                return $schedule->cinema->name;
            })->values(); // ambil ulang data hasil sortir : values()
        } elseif ($sortirAlfabet == 'DESC' ) {
            $movie->schedules = $movie->schedules->sortByDesc(function($schedule) {
                return $schedule->cinema->name;
            })->values();
        }

        return view('schedule.detail-film', compact('movie'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.movie.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd() : pengecekan data
        // $requst->all() :semua data dari requst form
        // dd($request->all());
        $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'genre' => 'required',
            'director' => 'required',
            'age_rating' => 'required',
            // mimes : memastikan file ekstensi file (bentuk file yang boleh di upload)
            'poster' => 'required|mimes:jpg,jpeg,png,svg,webp',
            'description' => 'required|min:10'
        ], [
            'title.required' => 'Judul wajib di isi',
            'duration.required' => 'Durasi wajib di isi',
            'genre.required' => 'Genre wajib di isi',
            'director.required' => 'Sutradara wajib di isi',
            'age_rating.required' => 'Usia Minimal wajib di isi',
            'poster.required' => 'Poster wajib di isi',
            'poster.mimes' => 'Format poster wajib berupa jpg, jpeg, png, svg, webp',
            'description.required' => 'Sinopsis wajib di isi',
            'description.min' => 'Deskripsi minimal 10 karakter'
        ]);
        // ambil data file dari input
        $poster = $request->file('poster');
        // buat nama file di public /storage
        // nama dibuat baru dan unik untuk menghindari nama file yang sama : <acak>_<waktu>.<ekstensi file>
        // getClientOriginalExtension() : mengambil ekstensi file asli
        $namaFile = Str::random(10) . "-poster." . $poster->getClientOriginalExtension();
        // simpan file ke public/storage
        // storeAs('folder tujuan', 'nama file')
        // visibility('public') : agar file dapat di akses publik
        $path = $poster->storeAs("posters", $namaFile, "public");

        $createData = Movie::create([
            'title' => $request->title,
            'duration' => $request->duration,
            'genre' => $request->genre,
            'director' => $request->director,
            'age_rating' => $request->age_rating,
            // yg di simpan di db adalah path file dari public/storage storeAs()
            'poster' => $path,
            'description' => $request->description,
            'actived' => 1
        ]);
        if ($createData) {
            return redirect()->route('admin.movies.index')->with('success', 'Berhasil Membuat Data');
        } else {
            return redirect()->back()->with('error', 'Gagal Menambahkan Data!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $movie = Movie::find($id);
        return view('admin.movie.edit', compact('movie'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd() : pengecekan data
        // $requst->all() :semua data dari requst form
        // dd($request->all());
        $request->validate([
            'title' => 'required',
            'duration' => 'required',
            'genre' => 'required',
            'director' => 'required',
            'age_rating' => 'required',
            // mimes : memastikan file ekstensi file (bentuk file yang boleh di upload)
            'poster' => '|mimes:jpg,jpeg,png,svg,webp',
            'description' => 'required|min:10'
        ], [
            'title.required' => 'Judul wajib di isi',
            'duration.required' => 'Durasi wajib di isi',
            'genre.required' => 'Genre wajib di isi',
            'director.required' => 'Sutradara wajib di isi',
            'age_rating.required' => 'Usia Minimal wajib di isi',
            'poster.required' => 'Poster wajib di isi',
            'poster.mimes' => 'Format poster wajib berupa jpg, jpeg, png, svg, webp',
            'description.required' => 'Sinopsis wajib di isi',
            'description.min' => 'Deskripsi minimal 10 karakter'
        ]);
        $movie = Movie::find($id);
        // cek jika ada poster baru
        if ($request->file('poster')) {
            // ambil lokasi poster lama
            $posterSebelumnya = storage_path('app/public/' . $movie->poster);
            // cek jika file ada di lokasi tsb
            if (file_exists($posterSebelumnya)) {
                // hapus file lama
                unlink($posterSebelumnya);
            }

            // ambil data file dari input
            $poster = $request->file('poster');
            // buat nama file di public /storage
            // nama dibuat baru dan unik untuk menghindari nama file yang sama : <acak>_<waktu>.<ekstensi file>
            // getClientOriginalExtension() : mengambil ekstensi file asli
            $namaFile = Str::random(10) . "-poster." . $poster->getClientOriginalExtension();
            // simpan file ke public/storage
            // storeAs('folder tujuan', 'nama file')
            // visibility('public') : agar file dapat di akses publik
            $path = $poster->storeAs("posters", $namaFile, "public");
        }

        $createData = Movie::where('id', $id)->update([
            'title' => $request->title,
            'duration' => $request->duration,
            'genre' => $request->genre,
            'director' => $request->director,
            'age_rating' => $request->age_rating,
            // ??tenary : (if, jika di ambil) ?? (else, jika tidak di ambil)
            'poster' => $path ?? $movie->poster,
            'description' => $request->description,
            'actived' => 1
        ]);
        if ($createData) {
            return redirect()->route('admin.movies.index')->with('success', 'Berhasil Mengubah Data');
        } else {
            return redirect()->back()->with('error', 'Gagal Menambahkan Data!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $schedules = Schedule::where('movie_id', $id)->count();
        if ($schedules) {
            return redirect()->route('admin.movies.index')->with('error', 'Tidak dapat menghapus data bioskop! Data tertaut dengan jadwal tayang');
        }
        $movie = Movie::findOrFail($id);
        $movie->delete();
        return redirect()->route('admin.movies.index')->with('success', 'Berhasil Menghapus Data Film!');
    }

    public function nonactive($id)
    {
        $movie = Movie::findOrFail($id);
        $movie->actived = $movie->actived == 1 ? 0 : 1;
        $movie->save();
        return redirect()->route('admin.movies.index')->with('success', 'Status Film Berhasil Diubah');
    }

    public function exportExcel()
    {

        // nama file yang akan terunduh
        $fileName = 'data-film.xlsx';
        // proses download
        return Excel::download(new MovieExport, $fileName);
    }

    public function trash()
    {
    $movieTrash = Movie::onlyTrashed()->get();
    return view('admin.movie.trash', compact('movieTrash'));
    }

    public function restore($id)
    {
        $movie = Movie::onlyTrashed()->find($id);
        $movie->restore();
        return redirect()->route('admin.movies.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $movie = Movie::onlyTrashed()->find($id);
        $movie->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus seutuhnya!');
    }

}
