@extends('layouts.app')

@section('title', 'Status Pesanan - TATAFIX')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-2xl font-semibold text-teal-600 mb-6">Status Pesanan</h1>
    
    <!-- Progress Bar -->
    <div class="mb-10">
        <div class="flex justify-between items-center mb-2">
            <div class="text-center">
                <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-sm">Pesanan Diterima</p>
                <p class="text-xs text-gray-500">19 Agustus 2024</p>
            </div>
            
            <div class="text-center">
                <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-sm">Sedang Pengerjaan</p>
                <p class="text-xs text-gray-500">22 Agustus 2024</p>
            </div>
            
            <div class="text-center">
                <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-sm">Finishing</p>
                <p class="text-xs text-gray-500">24 Agustus 2024</p>
            </div>
            
            <div class="text-center">
                <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-sm">Selesai</p>
                <p class="text-xs text-gray-500">25 Agustus 2024</p>
            </div>
        </div>
        <div class="relative pt-1">
            <div class="overflow-hidden h-2 mb-4 text-xs flex rounded bg-gray-200">
                <div class="w-full bg-orange-500 rounded"></div>
            </div>
        </div>
    </div>
    
    <!-- Service Details -->
    <div class="flex flex-col md:flex-row gap-6 mb-8">
        <div class="md:w-1/3">
            <img src="{{ asset('images/services/cleaning.jpg') }}" alt="Bersih Rumah" class="w-full h-auto rounded-lg">
        </div>
        <div class="md:w-2/3">
            <h2 class="text-xl font-semibold mb-2">Bersih Rumah</h2>
            <p class="text-gray-600 mb-2">2 Jam pengerjaan</p>
            <div class="text-orange-500 text-xl font-bold mt-auto">Rp350.000</div>
        </div>
    </div>
    
    <!-- Customer Details -->
    <div class="grid md:grid-cols-2 gap-8 mb-10">
        <div>
            <h3 class="font-semibold mb-3">Nama Pemesan</h3>
            <p>Keyra Renatha</p>
            
            <h3 class="font-semibold mt-4 mb-3">No Handphone</h3>
            <p>082378203123</p>
        </div>
        
        <div>
            <h3 class="font-semibold mb-3">Alamat</h3>
            <p>Jalan Buah Batu No. 123, Buah Batu</p>
            <p>Kota Bandung, Jawa Barat, 40265</p>
            <p>Indonesia</p>
            
            <h3 class="font-semibold mt-4 mb-3">Catatan Perbaikan</h3>
            <p>-</p>
        </div>
    </div>
    
    <!-- Feedback Section -->
    <div class="mt-10">
        <h2 class="text-xl font-semibold mb-6">Terima Kasih Telah Menggunakan Layanan Kami</h2>
        <p class="mb-6 text-gray-600">Rating layanan kami</p>
        
        <form action="#" method="POST">
            <div class="flex items-center mb-6">
                <div class="flex gap-2">
                    <input type="radio" name="rating" id="star-1" value="1" class="hidden">
                    <input type="radio" name="rating" id="star-2" value="2" class="hidden">
                    <input type="radio" name="rating" id="star-3" value="3" class="hidden">
                    <input type="radio" name="rating" id="star-4" value="4" class="hidden">
                    <input type="radio" name="rating" id="star-5" value="5" class="hidden">
                    
                    <label for="star-1" class="cursor-pointer">
                        <svg class="w-8 h-8 text-gray-300 hover:text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                        </svg>
                    </label>
                    <label for="star-2" class="cursor-pointer">
                        <svg class="w-8 h-8 text-gray-300 hover:text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                        </svg>
                    </label>
                    <label for="star-3" class="cursor-pointer">
                        <svg class="w-8 h-8 text-gray-300 hover:text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                        </svg>
                    </label>
                    <label for="star-4" class="cursor-pointer">
                        <svg class="w-8 h-8 text-gray-300 hover:text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                        </svg>
                    </label>
                    <label for="star-5" class="cursor-pointer">
                        <svg class="w-8 h-8 text-gray-300 hover:text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd" />
                        </svg>
                    </label>
                </div>
            </div>
            
            <div class="mb-6">
                <label for="feedback" class="block mb-2 text-sm font-medium text-gray-700">Tulis feedback Anda di bawah ini untuk membantu kami menjadi lebih baik</label>
                <textarea id="feedback" name="feedback" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
            </div>
            
            <div class="text-center">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-6 rounded-md transition duration-300">
                    Kirim Feedback
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Footer -->
<footer class="bg-teal-700 text-white mt-16">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-3 gap-8">
            <div>
                <h3 class="font-semibold mb-4">Akses Cepat</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:underline">Home</a></li>
                    <li><a href="#" class="hover:underline">Booking</a></li>
                    <li><a href="#" class="hover:underline">Jadwal</a></li>
                    <li><a href="#" class="hover:underline">Chat</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-semibold mb-4">Dukungan</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="hover:underline">Pusat Layanan</a></li>
                    <li><a href="#" class="hover:underline">Syarat dan Ketentuan</a></li>
                    <li><a href="#" class="hover:underline">Kebijakan Privasi</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-semibold mb-4">Media Sosial</h3>
                <div class="flex space-x-4">
                    <a href="#" class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                        <span class="text-teal-700">FB</span>
                    </a>
                    <a href="#" class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                        <span class="text-teal-700">IG</span>
                    </a>
                    <a href="#" class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                        <span class="text-teal-700">TW</span>
                    </a>
                    <a href="#" class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                        <span class="text-teal-700">YT</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="text-center text-sm mt-8">
            Copyright © 2024 TATAFIX All Right Reserved
        </div>
    </div>
</footer>
@endsection

@push('styles')
<style>
    /* Star rating styles */
    input[type="radio"]:checked + label svg {
        color: #F59E0B;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Star rating functionality
        const stars = document.querySelectorAll('input[name="rating"]');
        const starLabels = document.querySelectorAll('label[for^="star-"]');
        
        starLabels.forEach((label, index) => {
            label.addEventListener('mouseover', function() {
                // Highlight stars on hover
                for (let i = 0; i <= index; i++) {
                    starLabels[i].querySelector('svg').classList.remove('text-gray-300');
                    starLabels[i].querySelector('svg').classList.add('text-yellow-400');
                }
            });
            
            label.addEventListener('mouseout', function() {
                // Reset stars that aren't selected
                starLabels.forEach((star, i) => {
                    if (!stars[i].checked) {
                        star.querySelector('svg').classList.remove('text-yellow-400');
                        star.querySelector('svg').classList.add('text-gray-300');
                    }
                });
            });
        });
    });
</script>
@endpush