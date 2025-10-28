<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PromoExport;
use Yajra\DataTables\Facades\DataTables;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promos = Promo::all();
        return view('staff.promo.index', compact('promos'));
    }

    public function datatables()
    {


        
        $promos = Promo::query()->get();
        return DataTables::of($promos)
        ->addIndexColumn()
        ->addColumn('discount', function($promo) {
            if ($promo['type'] == 'percent') {
                return $promo['discount'] . '%';
            } elseif ($promo['type'] == 'rupiah') {
                return 'Rp. ' . number_format($promo['discount'], 0, ',', '.');
            }
        })
        ->addColumn('btnActions', function($promo){
            $btnEdit = '<a href="'. route('staff.promos.edit', $promo['id']) .'" class="btn btn-info me-2">Edit</a>';
            $btnDelete = '<form action="'. route('staff.promos.delete', $promo['id']) .'" method="POST">'.
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
        return view('staff.promo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'promo_code' => 'required',
            'type' => 'required',
            'discount' => 'required'
        ], [
            'promo_code.required' => 'Kode promo harus diisi',
            'type.required' => 'Tipe promo harus diisi',
            'discount.required' => 'Jumlah potongan harus diisi',
        ]);
        if ($request->type === 'percent' && $request->discount > 100) {
            return redirect()->back()->withInput()->with('error', 'Discount persen tidak boleh lebih dari 100');
        }
        if ($request->type === 'rupiah' && $request->discount < 1000) {
            return redirect()->back()->withInput()->with('error', 'Discount rupiah tidak boleh kurang dari 1000');
        }
        $createData = Promo::create([
            'promo_code' => $request->promo_code,
            'type' => $request->type,
            'discount' => $request->discount,
            'actived' => 1
        ]);
        if ($createData) {
            return redirect()->route('staff.promos.index')->with('success', 'Berhasil tambah data bioskop!');
        } else {
            return redirect()->back()->with('error', 'Gagal! silakan coba lagi');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Promo $promo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $promo = Promo::find($id);
        return view('staff.promo.edit', compact('promo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'promo_code' => 'required',
            'type' => 'required',
            'discount' => 'required'
        ], [
            'promo_code.required' => 'Kode promo harus diisi',
            'type.required' => 'Tipe promo harus diisi',
            'discount.required' => 'Jumlah potongan harus diisi',
        ]);
        if ($request->type === 'percent' && $request->discount > 100) {
            return redirect()->back()->withInput()->with('error', 'Discount persen tidak boleh lebih dari 100');
        }
        if ($request->type === 'rupiah' && $request->discount < 1000) {
            return redirect()->back()->withInput()->with('error', 'Discount rupiah tidak boleh kurang dari 1000');
        }
        $updateData = Promo::where('id', $id)->update([
            'promo_code' => $request->promo_code,
            'type' => $request->type,
            'discount' => $request->discount,
            'actived' => 1
        ]);
        if ($updateData) {
            return redirect()->route('staff.promos.index')->with('success', 'Berhasil mengubah data bioskop!');
        } else {
            return redirect()->back()->with('error', 'Gagal! silakan coba lagi');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $promo = Promo::findOrFail($id); // biar kalo ga ketemu langsung error 404
        $promo->delete(); // <-- ini yang ngapus

        return redirect()->route('staff.promos.index')->with('success', 'Berhasil menghapus Data Promo');
    }

    public function exportExcel()
    {
        // nama file yang akan terunduh
        $fileName = 'data-promo.xlsx';
        // proses download
        return Excel::download(new PromoExport, $fileName);
    }

    public function trash()
    {
    $promoTrash = Promo::onlyTrashed()->get();
    return view('staff.promo.trash', compact('promoTrash'));
    }

    public function restore($id)
    {
        $promo = Promo::onlyTrashed()->find($id);
        $promo->restore();
        return redirect()->route('staff.promos.index')->with('success', 'Berhasil mengembalikan data!');
    }

    public function deletePermanent($id)
    {
        $promo = Promo::onlyTrashed()->find($id);
        $promo->forceDelete();
        return redirect()->back()->with('success', 'Berhasil menghapus seutuhnya!');
    }
}
