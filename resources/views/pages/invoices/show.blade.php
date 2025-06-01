@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Invoice Header -->
        <div class="flex justify-between items-start p-6 border-b">
            <div>
                <div class="flex items-center mb-4">
                    <h1 class="text-2xl font-bold text-gray-800">Nomor <span class="bg-yellow-200 px-2 py-1">Invoice</span></h1>
                    <span class="ml-4 text-2xl font-semibold">{{ $invoice->invoice_number }}</span>
                </div>
                
                <div class="text-gray-700">
                    <h2 class="font-semibold text-lg mb-2">Bill to:</h2>
                    <p class="font-medium">{{ $invoice->nama_pemesan }}</p>
                    <p>{{ $invoice->no_handphone }}</p>
                    <p>{{ $invoice->alamat }}</p>
                </div>
            </div>
            
            <div>
                <div class="text-right mb-6">
                    @if($invoice->status === 'paid')
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Selesai</span>
                    @else
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">Menunggu Pembayaran</span>
                    @endif
                </div>
                
                <div class="text-gray-700">
                    <h2 class="font-semibold text-lg mb-2">Akun Bank</h2>
                    <div class="grid grid-cols-2 gap-x-4 text-sm">
                        <p class="font-medium">Nama Bank:</p>
                        <p>Bank Mandiri</p>
                        <p class="font-medium">Nomor Bank:</p>
                        <p>1234567687034</p>
                        <p class="font-medium">Nama Bank:</p>
                        <p>Bank BCA</p>
                        <p class="font-medium">Nomor Bank:</p>
                        <p>9857892758748</p>
                        <p class="font-medium">Tanggal:</p>
                        <p>{{ \Carbon\Carbon::parse($invoice->tanggal_invoice)->format('d F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Invoice Body -->
        <div class="p-6">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="text-left text-gray-500 uppercase text-xs border-b">
                        <th class="py-3 px-2">Jenis Layanan</th>
                        <th class="py-3 px-2">Down Payment</th>
                        <th class="py-3 px-2">Biaya Pelunasan</th>
                        <th class="py-3 px-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="py-4 px-2 uppercase text-gray-700">{{ $invoice->jenis_layanan }}</td>
                        <td class="py-4 px-2">Rp {{ number_format($invoice->down_payment, 0, ',', '.') }}</td>
                        <td class="py-4 px-2">Rp {{ number_format($invoice->biaya_pelunasan, 0, ',', '.') }}</td>
                        <td class="py-4 px-2 font-bold">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
            
            <div class="mt-8 flex justify-between">
                <div>
                    <h3 class="text-gray-700 font-semibold mb-2">Catatan:</h3>
                    <p class="text-sm text-gray-600">Silakan lakukan pembayaran ke salah satu rekening di atas dengan mencantumkan nomor invoice pada keterangan transfer.</p>
                </div>
                
                <div class="text-right">
                    <p class="text-gray-700 mb-1">Subtotal: <span class="font-semibold">Rp {{ number_format($invoice->total, 0, ',', '.') }}</span></p>
                    <p class="text-gray-700 mb-1">Pajak: <span class="font-semibold">Rp 0</span></p>
                    <p class="text-xl font-bold text-gray-800 mt-2">Total: Rp {{ number_format($invoice->total, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        
        <!-- Invoice Footer -->
        <div class="bg-gray-50 p-6 flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Terima kasih telah menggunakan layanan kami.</p>
            </div>
            
            <div class="flex space-x-3">
                @if(auth()->user()->hasRole('admin') && $invoice->status !== 'paid')
                <form action="{{ route('invoices.mark-paid', $invoice->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                        Tandai Lunas
                    </button>
                </form>
                @endif
                
                <a href="{{ route('invoices.download', $invoice->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Unduh PDF
                </a>
                
                <a href="{{ route('invoices.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection