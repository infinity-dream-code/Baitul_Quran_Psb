<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPeserta;
use App\Models\MasterNegara;
use App\Models\MasterSumberInformasi;
use App\Models\MasterProvinsi;
use App\Models\MasterKabupaten;
use App\Models\MasterKecamatan;
use App\Models\MasterPendidikanOrtu;
use App\Models\MasterPekerjaanOrtu;
use App\Models\MasterPenghasilanOrtu;
use App\Models\Tagihan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Rules\SafeImage;
use App\Support\SafeImageUpload;
use Firebase\JWT\JWT;

class PesertaController extends Controller
{
    public const SUMBER_INFORMASI = ['Keluarga', 'Teman', 'Media Sosial', 'Media Cetak'];

    public const FIELD_WAJIB = [
        'no_hp',
        'nik',
        'no_kk',
        'kewarganegaraan',
        'alamat_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'ibu_nama',
        'ayah_nama',
        'nama_sekolah',
    ];

    public const UPLOAD_WAJIB = [
        'foto',
        'dokumen_kk',
        'dokumen_akte_kelahiran',
        'dokumen_ijazah',
    ];

    public function index()
    {
        $peserta = auth()->user()->peserta()->with(['relasiGelombang', 'ujian', 'masterHarga', 'tagihan'])->first();

        $dataLengkap = $this->isDataLengkap($peserta);
        $step1Done = $peserta && (int) $peserta->status_paid === 1;
        $step2Done = $dataLengkap;
        $step3Done = $peserta && $peserta->ujian && $peserta->ujian->isNotEmpty();
        $step4Done = $peserta && in_array(strtolower((string) $peserta->status_ujian), ['lulus', 'gagal'], true);

        $saldoTagihan = $this->fetchSaldoTagihan($peserta);

        return view('peserta.index', compact(
            'peserta',
            'dataLengkap',
            'step1Done',
            'step2Done',
            'step3Done',
            'step4Done',
            'saldoTagihan'
        ));
    }

    public function lengkapi_data()
    {
        $peserta = auth()->user()->peserta ?? new DataPeserta();
        $peserta->load(['bantuan', 'masterHarga']);

        $negaraList = MasterNegara::all();
        $sumberInformasiList = self::SUMBER_INFORMASI;
        $provinsiList = MasterProvinsi::all();
        $pendidikanList = MasterPendidikanOrtu::all();
        $pekerjaanList = MasterPekerjaanOrtu::all();
        $penghasilanList = MasterPenghasilanOrtu::all();

        $kabupatenList = [];
        $kecamatanList = [];

        if ($peserta->id_provinsi) {
            $kabupatenList = MasterKabupaten::where('pmb_ref_provinsi_id', $peserta->id_provinsi)->get();
        }

        if ($peserta->id_kabupaten) {
            $kecamatanList = MasterKecamatan::where('pmb_ref_kota_id', $peserta->id_kabupaten)->get();
        }

        $saldoTagihan = $this->fetchSaldoTagihan($peserta instanceof DataPeserta && $peserta->exists ? $peserta : null);

        return view('peserta.lengkapi_data', compact(
            'peserta',
            'negaraList',
            'sumberInformasiList',
            'provinsiList',
            'kabupatenList',
            'kecamatanList',
            'pendidikanList',
            'pekerjaanList',
            'penghasilanList',
            'saldoTagihan'
        ));
    }

    public function simpan_bantuan(Request $request)
    {
        $request->validate([
            'no_kks' => 'nullable|string|max:20',
            'no_kps' => 'nullable|string|max:20',
            'usulan_pip' => 'nullable|string',
            'nomor_kip' => 'nullable',
            'nama_kip' => 'nullable|string|max:100',
            'alasan_menolak_kip' => 'nullable|string',
            'no_reg_akta_lahir' => 'nullable',
        ]);

        $user = auth()->user();
        $peserta = $user->peserta;

        if (!$peserta) {
            return redirect()->back()->with('error', 'Data peserta tidak ditemukan.');
        }

        $peserta->bantuan()->updateOrCreate(
            ['id_peserta' => $peserta->id],
            [
                'no_kks' => $request->no_kks,
                'no_kps' => $request->no_kps,
                'usulan_pip' => $request->usulan_pip,
                'nomor_kip' => $request->nomor_kip,
                'nama_kip' => $request->nama_kip,
                'alasan_menolak_kip' => $request->alasan_menolak_kip,
                'no_reg_akta_lahir' => $request->no_reg_akta_lahir,
            ]
        );

        return redirect()->back()->with('success', 'Data bantuan berhasil disimpan!');
    }

    public function simpan_data(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16',
            'no_akta_lahir' => 'required',
            'no_kk' => 'required|digits:16',
            'ibu_nik' => 'nullable|digits:16',
            'ayah_nik' => 'nullable|digits:16',
            'nisn' => 'nullable|digits:10',
            'tahun_lulus' => 'nullable|digits:4',
            'no_bpjs' => 'nullable|string|max:30',
            'nama_sumber' => ['nullable', \Illuminate\Validation\Rule::in(self::SUMBER_INFORMASI)],
            'foto' => ['nullable', 'file', new SafeImage()],
            'dokumen_kk' => ['nullable', 'file', new SafeImage()],
            'dokumen_akte_kelahiran' => ['nullable', 'file', new SafeImage()],
            'dokumen_ijazah' => ['nullable', 'file', new SafeImage()],
        ], [], [
            'foto' => 'Foto',
            'dokumen_kk' => 'Kartu Keluarga',
            'dokumen_akte_kelahiran' => 'Akta Kelahiran',
            'dokumen_ijazah' => 'Ijazah / Surat Keterangan Lulus',
            'no_bpjs' => 'Nomor Kartu BPJS',
            'nama_sumber' => 'Dapat Informasi Dari',
        ]);

        $user = auth()->user();
        $peserta = $user->peserta ?? new DataPeserta();
        $peserta->id_user = $user->id;

        $fillable = [
            'nama_peserta', 'nik', 'tempat_lahir', 'tanggal_lahir',
            'nisn', 'no_akta_lahir', 'no_kk', 'no_bpjs',
            'kewarganegaraan', 'email', 'no_hp', 'alamat_lengkap',
            'dusun', 'id_provinsi', 'id_kabupaten', 'id_kecamatan', 'kode_pos',
            'nama_sekolah', 'tahun_lulus',
            'ibu_nama', 'ibu_tanggal_lahir', 'ibu_nik',
            'id_pendidikan_ibu', 'id_pekerjaan_ibu', 'id_penghasilan_ibu', 'ibu_no_tlp',
            'ayah_nama', 'ayah_tanggal_lahir', 'ayah_nik',
            'id_pendidikan_ayah', 'id_pekerjaan_ayah', 'id_penghasilan_ayah', 'ayah_no_tlp',
            'jml_saudara_kandung', 'jml_saudara_yayasan', 'nama_sumber',
        ];

        foreach ($fillable as $field) {
            $peserta->$field = $request->$field ?? $peserta->$field;
        }

        if ($request->gender) {
            $peserta->gender = $request->gender === 'Laki-laki' ? 'L' : 'P';
        }

        if ($request->filled('id_pendidikan_ibu')) {
            $peserta->ibu_pendidikan = MasterPendidikanOrtu::find($request->id_pendidikan_ibu)->nama ?? null;
        }
        if ($request->filled('id_pendidikan_ayah')) {
            $peserta->ayah_pendidikan = MasterPendidikanOrtu::find($request->id_pendidikan_ayah)->nama ?? null;
        }
        if ($request->filled('nama_sumber')) {
            $peserta->id_sumber = MasterSumberInformasi::where('nama', $request->nama_sumber)->value('id');
        }

        $tahun = optional($peserta->relasiGelombang)->tahun ?? date('Y');

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $img = (new ImageManager(new Driver()))->read($foto->getRealPath());
            if (round($img->width() / $img->height(), 2) != round(3 / 4, 2)) {
                return back()->withErrors(['foto' => 'Foto harus berukuran 3x4 (rasio 3:4)'])->withInput();
            }
            $this->replaceUpload($peserta, 'foto', $foto, "uploads/foto/foto-$tahun");
        }

        $dokumen = [
            'dokumen_kk' => "uploads/dokumen/kk-$tahun",
            'dokumen_akte_kelahiran' => "uploads/dokumen/akta-$tahun",
            'dokumen_ijazah' => "uploads/dokumen/ijazah-$tahun",
        ];
        foreach ($dokumen as $field => $directory) {
            if ($request->hasFile($field)) {
                $this->replaceUpload($peserta, $field, $request->file($field), $directory);
            }
        }

        $peserta->save();

        return redirect('PmbMstPendaftarans/lengkapi_data')->with('success', 'Data berhasil disimpan!');
    }

    public function suratKelulusan()
    {
        $peserta = auth()->user()->peserta;
        if (!$peserta || !in_array(strtolower((string) $peserta->status_ujian), ['lulus', 'gagal'], true)) {
            abort(403, 'Surat kelulusan belum tersedia.');
        }

        $gelombang = $peserta->relasiGelombang;
        $tahun_akademik = $gelombang?->tahun_akademik ?? '-';
        $tanggalSekarang = Carbon::now()->translatedFormat('d F Y');
        $tagihan = Tagihan::where('id_peserta', $peserta->id)->orderByDesc('created_at')->first();

        return view('peserta.surat-kelulusan', compact('peserta', 'tahun_akademik', 'tanggalSekarang', 'tagihan'));
    }

    public function suratRegistrasi()
    {
        $peserta = auth()->user()->peserta;
        if (!$peserta || strtolower((string) $peserta->status_ujian) !== 'lulus') {
            abort(403, 'Surat registrasi hanya untuk peserta yang lulus.');
        }

        $gelombang = $peserta->relasiGelombang;
        $tahun_akademik = $gelombang?->tahun_akademik ?? '-';
        $tanggalSekarang = Carbon::now()->translatedFormat('d F Y');
        $tagihan = Tagihan::where('id_peserta', $peserta->id)->orderByDesc('created_at')->first();

        if (!$tagihan && $peserta->masterHarga && is_array($peserta->masterHarga->detail)) {
            $detail = collect($peserta->masterHarga->detail)->map(function ($d) {
                return [
                    'nama_tagihan' => $d['nama_tagihan'] ?? '-',
                    'nominal' => (int) ($d['biaya'] ?? 0),
                ];
            })->toArray();
            $tagihan = (object) [
                'detail' => $detail,
                'biaya_daful' => array_sum(array_column($detail, 'nominal')),
            ];
        }

        return view('peserta.surat-registrasi', compact('peserta', 'tahun_akademik', 'tanggalSekarang', 'tagihan'));
    }

    public function isDataLengkap(?DataPeserta $peserta): bool
    {
        if (!$peserta || !$peserta->exists) {
            return false;
        }

        foreach (self::FIELD_WAJIB as $field) {
            if (empty($peserta->$field)) {
                return false;
            }
        }

        foreach (self::UPLOAD_WAJIB as $field) {
            if (empty($peserta->$field)) {
                return false;
            }
        }

        return true;
    }

    private function fetchSaldoTagihan(?DataPeserta $peserta): array
    {
        $fallback = [
            'ok' => false,
            'items' => [],
            'total' => 0,
            'terbayar' => 0,
            'sisa' => 0,
            'status_lokal_paid' => $peserta ? (int) $peserta->status_paid === 1 : false,
            'status_lokal_regis' => $peserta ? (int) $peserta->status_pembayaran_registrasi === 1 : false,
        ];

        if (!$peserta || !$peserta->no_pendaftaran) {
            return $fallback;
        }

        try {
            $jwtKey = config('services.psb.jwt_key', '53c2f9aace5478a11815c65fcdb1a3dc29b60c3e102489384e3c1701f4355fa4');
            $token = JWT::encode(['nomor_pendaftaran' => $peserta->no_pendaftaran], $jwtKey, 'HS256');

            $response = Http::timeout(8)
                ->connectTimeout(5)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post(config('services.psb.ws_url'), [
                    'token' => $token,
                    'method' => 'CekTagihan',
                ]);

            if (!$response->successful()) {
                return $fallback;
            }

            $result = $response->json();
            $rows = $result['data'] ?? null;
            if (!is_array($rows)) {
                return $fallback;
            }

            // Normalisasi: kadang data[0] adalah list, kadang tiap item tagihan.
            if (isset($rows[0]) && is_array($rows[0]) && array_is_list($rows) === false) {
                $rows = [$rows];
            }
            if (isset($rows[0]) && is_array($rows[0]) && isset($rows[0][0])) {
                $rows = $rows[0];
            }

            $items = [];
            $total = 0;
            $terbayar = 0;
            $sisa = 0;

            foreach ($rows as $row) {
                if (!is_array($row)) {
                    continue;
                }

                $nama = $row['NamaTagihan'] ?? $row['nama_tagihan'] ?? $row['BILLNM'] ?? 'Tagihan';
                $billAm = (int) ($row['TotalTagihan'] ?? $row['BILLAM'] ?? $row['nominal'] ?? 0);
                $billPaid = (int) ($row['TotalBayar'] ?? $row['BILLPAID'] ?? $row['terbayar'] ?? 0);
                $left = (int) ($row['SisaTagihan'] ?? $row['PAYMENTLEFT'] ?? $row['sisa'] ?? max(0, $billAm - $billPaid));
                $status = (string) ($row['StatusBayar'] ?? $row['PAIDST'] ?? ($left <= 0 ? '1' : '0'));

                // Jika hanya ada StatusBayar tanpa nominal (format sync login), skip agregat kosong.
                if ($billAm === 0 && $billPaid === 0 && $left === 0 && !isset($row['NamaTagihan']) && !isset($row['BILLNM'])) {
                    continue;
                }

                $items[] = [
                    'nama' => $nama,
                    'total' => $billAm,
                    'terbayar' => $billPaid,
                    'sisa' => $left,
                    'lunas' => $status === '1' || $left <= 0,
                ];
                $total += $billAm;
                $terbayar += $billPaid;
                $sisa += $left;
            }

            if (empty($items)) {
                return $fallback;
            }

            return [
                'ok' => true,
                'items' => $items,
                'total' => $total,
                'terbayar' => $terbayar,
                'sisa' => $sisa,
                'status_lokal_paid' => (int) $peserta->status_paid === 1,
                'status_lokal_regis' => (int) $peserta->status_pembayaran_registrasi === 1,
            ];
        } catch (\Throwable $e) {
            Log::warning('peserta.fetch_saldo_failed', [
                'peserta_id' => $peserta->id,
                'error' => $e->getMessage(),
            ]);
            return $fallback;
        }
    }

    private function replaceUpload(DataPeserta $peserta, string $field, \Illuminate\Http\UploadedFile $file, string $directory): void
    {
        $old = $peserta->$field;
        $peserta->$field = SafeImageUpload::store($file, $directory);

        if ($old && $old !== $peserta->$field && Storage::disk('public')->exists($old)) {
            Storage::disk('public')->delete($old);
        }
    }
}
