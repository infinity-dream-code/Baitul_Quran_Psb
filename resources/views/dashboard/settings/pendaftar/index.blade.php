@extends('dashboard.template')

@section('content')
<div class="bg-white p-6 shadow rounded-lg">
    <h1 class="text-xl font-semibold text-gray-800 mb-4">Set On/Off PMB</h1>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 text-sm">
            <thead class="bg-green-600 text-white uppercase text-xs">
                <tr>
                    <th class="border px-3 py-2">#</th>
                    <th class="border px-3 py-2">Fakultas</th>
                    <th class="border px-3 py-2 text-center">Aktif / Nonaktifkan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fakultas as $i => $f)
                <tr class="hover:bg-gray-50 transition">
                    <td class="border px-3 py-2">{{ $i+1 }}</td>
                    <td class="border px-3 py-2 font-medium">{{ strtoupper($f->fakultas) }}</td>
                    <td class="border px-3 py-2 text-center">
                        <label class="toggle-switch">
                            <input type="checkbox" {{ $f->aktif ? 'checked' : '' }} onchange="toggleFakultas({{ $f->id }})">
                            <span class="toggle-slider"></span>
                        </label>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleFakultas(id) {
    fetch("{{ url('fakultas/toggle') }}/" + id, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            "Accept": "application/json"
        }
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            alert("Update gagal!");
        }
    })
    .catch(() => alert("Update gagal!"));
}
</script>
@endsection
