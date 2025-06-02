<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            padding: 20px 0;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        .invoice-number {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .invoice-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .customer-details, .bank-details {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .total-section {
            text-align: right;
            margin-top: 30px;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <div class="logo">TataFix</div>
                <p>Jasa Perbaikan Rumah Terpercaya</p>
            </div>
            <div>
                <div class="invoice-number">Invoice #{{ $invoice->invoice_number }}</div>
                <div class="invoice-status {{ $invoice->status === 'paid' ? 'status-paid' : 'status-pending' }}">
                    {{ $invoice->status === 'paid' ? 'LUNAS' : 'BELUM LUNAS' }}
                </div>
                <p>Tanggal: {{ \Carbon\Carbon::parse($invoice->tanggal_invoice)->format('d F Y') }}</p>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; margin-top: 30px;">
            <div class="customer-details">
                <div class="section-title">Ditagihkan kepada:</div>
                <p>{{ $invoice->nama_pemesan }}</p>
                @if($invoice->no_handphone)
                <p>{{ $invoice->no_handphone }}</p>
                @endif
                @if($invoice->alamat)
                <p>{{ $invoice->alamat }}</p>
                @endif
            </div>
            
            <div class="bank-details">
                <div class="section-title">Informasi Pembayaran:</div>
                <p>Bank Mandiri<br>
                No. Rekening: 1234567687034<br>
                a.n. TataFix Indonesia</p>
                <p>Bank BCA<br>
                No. Rekening: 9857892758748<br>
                a.n. TataFix Indonesia</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Jenis Layanan</th>
                    <th>Down Payment</th>
                    <th>Biaya Pelunasan</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $invoice->jenis_layanan }}</td>
                    <td>Rp {{ number_format($invoice->down_payment, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($invoice->biaya_pelunasan, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total-section">
            <p>Subtotal: Rp {{ number_format($invoice->total, 0, ',', '.') }}</p>
            <p>Pajak: Rp 0</p>
            <p class="total">Total: Rp {{ number_format($invoice->total, 0, ',', '.') }}</p>
        </div>

        <div class="footer">
            <p>Terima kasih telah menggunakan layanan TataFix.</p>
            <p>Jika ada pertanyaan, silakan hubungi kami di support@tatafix.com atau (021) 123-4567</p>
        </div>
    </div>
</body>
</html>
