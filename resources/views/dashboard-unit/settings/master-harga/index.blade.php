@extends('dashboard-unit.template')

@section('content')
<div class="bg-white p-6 shadow rounded-lg m-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-semibold text-gray-800">Master Harga PMB</h1>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 text-sm">
            <thead class="bg-green-600 text-white uppercase text-xs">
                <tr>
                    <th class="border px-3 py-2">#</th>
                    <th class="border px-3 py-2">Nama Jalur</th>
                    <th class="border px-3 py-2">Tahap</th>
                    <th class="border px-3 py-2">Sekolah</th>
                    <th class="border px-3 py-2">Jurusan</th>
                    <th class="border px-3 py-2">Biaya Registrasi</th>
                    <th class="border px-3 py-2">Biaya Daftar Ulang</th>
                    <th class="border px-3 py-2 text-center">Aktif</th>
                    <th class="border px-3 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($masterHarga as $index => $mh)
                <tr class="hover:bg-gray-50 transition" data-jalur="{{ $mh->nama_jalur }}" data-prodi="{{ $mh->nama_prodi }}">
                    <td class="border px-3 py-2">{{ $masterHarga->firstItem() + $index }}</td>
                    <td class="border px-3 py-2 font-medium">{{ $mh->nama_jalur }}</td>
                    <td class="border px-3 py-2">Tahap {{ $mh->nama_gelombang }}</td>
                    <td class="border px-3 py-2">{{ $mh->nama_fakultas }}</td>
                    <td class="border px-3 py-2">{{ $mh->nama_prodi }}</td>
                    <td class="border px-3 py-2">Rp {{ number_format($mh->harga_final, 0, ',', '.') }}</td>
                    <td class="border px-3 py-2">Rp {{ number_format($mh->harga_registrasi, 0, ',', '.') }}</td>
                    <td class="border px-3 py-2 text-center">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer toggle-harga" data-id="{{ $mh->id }}" {{ $mh->active ? 'checked' : '' }} onchange="toggleHargaActive({{ $mh->id }})">
                            <div class="w-12 h-6 bg-gray-300 rounded-full peer-checked:bg-green-500 relative after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-6 peer-checked:after:border-white"></div>
                        </label>
                    </td>
                    <td class="border px-3 py-2 text-center">
                        <a href="{{ url('PmbRefMasterHargaPendaftarans/' . str_replace(' ', '-', $role ?? 'admin') . '/edit/' . $mh->id) }}"
                           class="text-blue-600 hover:text-blue-800 transition">
                            <i class="fas fa-edit text-lg"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="border px-3 py-6 text-center text-gray-500">Belum ada data Master Harga</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <p class="text-sm text-gray-600">
            Menampilkan {{ $masterHarga->firstItem() ?? 0 }}–{{ $masterHarga->lastItem() ?? 0 }}
            dari {{ $masterHarga->total() }} data
            @if($masterHarga->lastPage() > 1)
                (halaman {{ $masterHarga->currentPage() }}/{{ $masterHarga->lastPage() }})
            @endif
        </p>
        <div>{{ $masterHarga->links() }}</div>
    </div>
</div>

<script>
function toggleHargaActive(id) {
    const currentCheckbox = document.querySelector(`.toggle-harga[data-id="${id}"]`);
    const currentRow = currentCheckbox.closest('tr');
    const currentJalur = currentRow.getAttribute('data-jalur');
    const currentProdi = currentRow.getAttribute('data-prodi');
    const isActive = currentCheckbox.checked;

    fetch(`/master-harga/toggle-active/${id}`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            "Accept": "application/json"
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.querySelectorAll('tr[data-jalur][data-prodi]').forEach(row => {
                if (row.getAttribute('data-jalur') === currentJalur && row.getAttribute('data-prodi') === currentProdi) {
                    const checkbox = row.querySelector('.toggle-harga');
                    if (checkbox) checkbox.checked = isActive;
                }
            });
        } else {
            alert("Update gagal!");
            currentCheckbox.checked = !isActive;
        }
    })
    .catch(() => {
        alert("Update gagal!");
        currentCheckbox.checked = !isActive;
    });
}
</script>
@endsection
