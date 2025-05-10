@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6">
                <div class="text-center mb-8">
                    <div class="relative inline-block">
                        <img src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : asset('images/default-avatar.jpg') }}" 
                             alt="Profile Image" 
                             class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                    </div>
                    <h2 class="mt-4 text-2xl font-bold text-gray-900">{{ auth()->user()->name }}</h2>
                    <p class="text-gray-500">{{ auth()->user()->email }}</p>
                    <a href="{{ route('profile.edit') }}" 
                       class="mt-4 inline-flex items-center px-4 py-2 border border-blue-600 text-sm font-medium rounded-md text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Edit Profil
                    </a>
                </div>
                
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Akun</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Nama Lengkap</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ auth()->user()->name }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">No. Handphone</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ auth()->user()->phone ?? '-' }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Email</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Tombol Logout -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex justify-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-300 disabled:opacity-25 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection