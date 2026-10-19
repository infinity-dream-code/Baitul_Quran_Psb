<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterAkademik;
use App\Models\MasterGelombang;

class MasterAkademikController extends Controller
{
    public function index()
    {
        $akademik = MasterAkademik::orderByDesc('tahun_mulai')->paginate(10);

        return view('dashboard.master-data.tahun-akademik.index', compact('akademik'));
    }

    public function create()
    {
        return view('dashboard.master-data.tahun-akademik.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required|string|max:50|unique:master_akademik,tahun_akademik',
            'tahun_mulai' => 'required|integer',
            'tahun_selesai' => 'required|integer|gt:tahun_mulai',
        ]);

        MasterAkademik::create([
            'tahun_akademik' => $request->tahun_akademik,
            'tahun_mulai' => $request->tahun_mulai,
            'tahun_selesai' => $request->tahun_selesai,
        ]);

        return redirect()->route('master.tahun_akademik')
            ->with('success', 'Tahun Akademik berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $akademik = MasterAkademik::findOrFail($id);

        return view('dashboard.master-data.tahun-akademik.edit', compact('akademik'));
    }

    public function update(Request $request, $id)
    {
        $akademik = MasterAkademik::findOrFail($id);

        $request->validate([
            'tahun_akademik' => 'required|string|max:50|unique:master_akademik,tahun_akademik,' . $akademik->id,
            'tahun_mulai' => 'required|integer',
            'tahun_selesai' => 'required|integer|gt:tahun_mulai',
        ]);

        $oldTahunAkademik = $akademik->tahun_akademik;

        $akademik->update([
            'tahun_akademik' => $request->tahun_akademik,
            'tahun_mulai' => $request->tahun_mulai,
            'tahun_selesai' => $request->tahun_selesai,
        ]);

        // Sinkronkan string tahun_akademik di tahap (gelombang) yang memakai nilai lama
        if ($oldTahunAkademik !== $request->tahun_akademik) {
            MasterGelombang::where('tahun_akademik', $oldTahunAkademik)
                ->update([
                    'tahun_akademik' => $request->tahun_akademik,
                    'tahun' => $request->tahun_mulai,
                ]);
        }

        return redirect()->route('master.tahun_akademik')
            ->with('success', 'Tahun Akademik berhasil diperbarui.');
    }
}
