<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil Tukang</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen p-8">

    <div class="max-w-xl mx-auto bg-white p-8 rounded-2xl shadow-lg">

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Edit Profil Tukang</h2>

        @if ($errors->any())
            <div class="mb-6 bg-red-100 p-4 rounded-lg text-red-600">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex justify-center mb-8">
        @if($tukang->photo)
    <img src="{{ asset($tukang->photo) }}" alt="Foto" class="w-14 h-14 rounded-full object-cover mx-auto">
@else
    <div class="w-14 h-14 rounded-full bg-gray-200 mx-auto"></div>
@endif

        </div>

        <form method="POST" action="{{ route('tukang.update', $tukang->user_id) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block mb-2 font-semibold">Nama <span class="text-red-600">*</span></label>
                <input type="text" name="name" value="{{ old('name', $tukang->name) }}" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Domisili <span class="text-red-600">*</span></label>
                <input type="text" name="address" value="{{ old('address', $tukang->address) }}" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div>
                <label class="block mb-2 font-semibold">No. Handphone <span class="text-red-600">*</span></label>
                <input type="text" name="phone" value="{{ old('phone', $tukang->phone) }}" class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-400" pattern="[0-9]+" title="Hanya angka yang diperbolehkan" required>
            </div>

            <div>
                <label class="block mb-2 font-semibold text-gray-400">Email</label>
                <input type="email" name="email" value="{{ $tukang->email }}" class="w-full border rounded-lg p-3 bg-gray-100 text-gray-600 cursor-not-allowed" readonly>
            </div>

            <div>
                <label class="block mb-2 font-semibold">Ganti Foto</label>
                <input type="file" name="photo" class="w-full border rounded-lg p-3">
            </div>

            <div class="flex justify-end gap-4 pt-6">
                <a href="{{ route('tukang.index') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg text-sm">Batal</a>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold">Update</button>
            </div>

        </form>

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
