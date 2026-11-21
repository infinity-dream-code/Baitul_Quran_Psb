<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brosur;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BrosurController extends Controller
{
    private const MAX_KB = 20480; // 20 MB

    public function index()
    {
        $brosurs = Brosur::with('user')->orderByDesc('tanggal_upload')->get();
        return view('dashboard.settings.brosur.index', compact('brosurs'));
    }

    public function create()
    {
        return view('dashboard.settings.brosur.create');
    }

    public function show(Brosur $setting_brosur)
    {
        return redirect()->route('setting-brosur.edit', $setting_brosur);
    }

    public function store(Request $request)
    {
        if (Brosur::count() >= 8) {
            return redirect()->route('setting-brosur.index')->with('error', 'Maksimal hanya 8 brosur yang bisa diupload.');
        }

        $request->validate([
            'brosur' => 'required|mimes:pdf,jpg,jpeg,png|max:' . self::MAX_KB,
        ], [
            'brosur.max' => 'Ukuran brosur maksimal 20 MB.',
            'brosur.mimes' => 'Format brosur harus PDF, JPG, JPEG, atau PNG.',
        ]);

        $path = $this->storeBrosurFile($request->file('brosur'));

        Brosur::create([
            'id_user' => Auth::id(),
            'brosur' => $path,
            'tanggal_upload' => now(),
        ]);

        return redirect()->route('setting-brosur.index')->with('success', 'Brosur berhasil ditambahkan');
    }

    public function edit(Brosur $setting_brosur)
    {
        return view('dashboard.settings.brosur.edit', compact('setting_brosur'));
    }

    public function update(Request $request, Brosur $setting_brosur)
    {
        $request->validate([
            'brosur' => 'nullable|mimes:pdf,jpg,jpeg,png|max:' . self::MAX_KB,
        ], [
            'brosur.max' => 'Ukuran brosur maksimal 20 MB.',
            'brosur.mimes' => 'Format brosur harus PDF, JPG, JPEG, atau PNG.',
        ]);

        if ($request->hasFile('brosur')) {
            if ($setting_brosur->brosur && Storage::disk('public')->exists($setting_brosur->brosur)) {
                Storage::disk('public')->delete($setting_brosur->brosur);
            }

            $setting_brosur->brosur = $this->storeBrosurFile($request->file('brosur'));
            $setting_brosur->tanggal_upload = now();
        }

        $setting_brosur->id_user = Auth::id();
        $setting_brosur->save();

        return redirect()->route('setting-brosur.index')->with('success', 'Brosur berhasil diperbarui');
    }

    public function destroy(Brosur $setting_brosur)
    {
        if ($setting_brosur->brosur && Storage::disk('public')->exists($setting_brosur->brosur)) {
            Storage::disk('public')->delete($setting_brosur->brosur);
        }

        $setting_brosur->delete();
        return redirect()->route('setting-brosur.index')->with('success', 'Brosur berhasil dihapus');
    }

    private function storeBrosurFile(\Illuminate\Http\UploadedFile $file): string
    {
        $ext = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'bin');
        $base = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safe = Str::slug($base) ?: 'brosur';
        $name = $safe . '-' . now()->format('YmdHis') . '-' . Str::random(4) . '.' . $ext;

        return $file->storeAs('brosur', $name, 'public');
    }
}
