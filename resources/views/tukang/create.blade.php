<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Tukang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen p-8">

    <div class="max-w-xl mx-auto bg-white p-8 rounded-2xl shadow-lg">

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Tambah Tukang Baru</h2>

        @if ($errors->any())
            <div class="mb-6 bg-red-100 p-4 rounded-lg text-red-600">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('tukang.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block mb-2 font-semibold">Nama <span class="text-red-600">*</span></label>
                <input type="text" name="name" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Domisili <span class="text-red-600">*</span></label>
                <input type="text" name="address" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div>
                <label class="block mb-2 font-semibold">No. Handphone <span class="text-red-600">*</span></label>
                <input type="text" name="phone" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Email <span class="text-red-600">*</span></label>
                <input type="email" name="email" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Foto <span class="text-red-600">*</span></label>
                <input type="file" name="photo" class="w-full border rounded-lg p-3" required>
            </div>

            <div class="flex justify-end gap-4 pt-6">
                <a href="{{ route('tukang.index') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg text-sm">Batal</a>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold">Simpan</button>
            </div>

        </form>

    </div>

</body>
</html>
