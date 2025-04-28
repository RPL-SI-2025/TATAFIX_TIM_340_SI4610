@extends('Layout.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Profil Saya</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Nama</label>
                    <p class="mt-1 text-lg">{{ Auth::user()->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">Email</label>
                    <p class="mt-1 text-lg">{{ Auth::user()->email }}</p>
                </div>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Nomor Telepon</label>
                    <p class="mt-1 text-lg">{{ Auth::user()->phone }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">Alamat</label>
                    <p class="mt-1 text-lg">{{ Auth::user()->address }}</p>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end space-x-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
