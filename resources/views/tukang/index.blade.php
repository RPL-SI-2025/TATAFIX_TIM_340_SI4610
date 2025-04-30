<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Data Tukang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen p-8">

    <div class="max-w-7xl mx-auto">
        <div class="bg-white p-8 rounded-2xl shadow-lg space-y-6">

            <div class="flex justify-between items-center">
                <h2 class="text-3xl font-bold text-gray-800">Master Data Tukang</h2>
                <a href="{{ route('tukang.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow transition">
                    <i class="ri-add-line text-lg"></i> Tambah
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-gray-700">
                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="p-4 text-left">Foto</th>
                            <th class="p-4 text-left">Nama</th>
                            <th class="p-4 text-left">Domisili</th>
                            <th class="p-4 text-left">No. Handphone</th>
                            <th class="p-4 text-left">Email</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($tukangs as $tukang)
                        <tr class="hover:bg-gray-50">
                            <td class="p-4">
                            @if($tukang->photo)
    <img src="{{ asset($tukang->photo) }}" alt="Foto" class="w-14 h-14 rounded-full object-cover mx-auto">
@else
    <div class="w-14 h-14 rounded-full bg-gray-200 mx-auto"></div>
@endif

                            </td>
                            <td class="p-4">{{ $tukang->name }}</td>
                            <td class="p-4">{{ $tukang->address }}</td>
                            <td class="p-4">{{ $tukang->phone }}</td>
                            <td class="p-4">{{ $tukang->email }}</td>
                            <td class="p-4">
                                <div class="flex items-center justify-center gap-4">
                                    <a href="{{ route('tukang.edit', $tukang->user_id) }}" class="text-blue-500 hover:text-blue-700 transition">
                                        <i class="ri-edit-2-line text-xl"></i>
                                    </a>
                                    <form action="{{ route('tukang.destroy', $tukang->user_id) }}" method="POST" onsubmit="return confirm('Yakin ingin hapus?')" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                            <i class="ri-delete-bin-6-line text-xl"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center p-8 text-gray-400">Belum ada data tukang.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-6">
                {{ $tukangs->links() }}
            </div>

        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

</body>
</html>
