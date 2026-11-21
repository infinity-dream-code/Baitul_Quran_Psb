@extends('dashboard.template')

@section('content')
<div class="bg-white p-6 shadow rounded-lg">
    <h1 class="text-xl font-semibold text-gray-800 mb-4">Set Tahap Pendaftaran</h1>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 text-sm">
            <thead class="bg-green-600 text-white">
                <tr>
                    <th class="border px-3 py-2 text-left text-xs font-semibold uppercase">#</th>
                    <th class="border px-3 py-2 text-left text-xs font-semibold uppercase">Tahap</th>
                    <th class="border px-3 py-2 text-left text-xs font-semibold uppercase">Tahun Akademik</th>
                    <th class="border px-3 py-2 text-left text-xs font-semibold uppercase">Pengumuman</th>
                    <th class="border px-3 py-2 text-center text-xs font-semibold uppercase">Aktifkan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gelombangs as $i => $g)
                <tr class="hover:bg-gray-50">
                    <td class="border px-3 py-2 text-sm text-gray-700">{{ $i+1 }}</td>
                    <td class="border px-3 py-2 text-sm text-gray-700 font-medium">Tahap {{ $g->gelombang }}</td>
                    <td class="border px-3 py-2 text-sm text-gray-700">{{ $g->tahun }}</td>
                    <td class="border px-3 py-2 text-sm text-gray-700">{{ $g->pengumuman->format('d-m-Y') }}</td>
                    <td class="border px-3 py-2 text-center">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" {{ $g->is_active ? 'checked' : '' }} onchange="toggleGelombang({{ $g->id }})">
                            <div class="w-12 h-6 bg-gray-300 rounded-full peer-checked:bg-green-500 relative after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-6 peer-checked:after:border-white"></div>
                        </label>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
function toggleGelombang(id) {
    fetch("{{ url('gelombang/toggle') }}/" + id, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            "Content-Type": "application/json",
            "Accept": "application/json"
        },
        body: JSON.stringify({})
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            alert("Update gagal!");
        }
    })
    .catch(err => console.error(err));
}
</script>
@endsection
