<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $transaction->invoice_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: 80mm auto;
            margin: 0;
        }

        body {
            font-family: 'Courier New', monospace;
            width: 80mm;
            margin: 0 auto;
            padding: 10mm 5mm;
            background: white;
            font-size: 12px;
            line-height: 1.4;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px dashed #000;
        }

        .store-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .store-info {
            font-size: 11px;
            line-height: 1.3;
        }

        .divider {
            border-bottom: 1px dashed #000;
            margin: 10px 0;
        }

        .divider-solid {
            border-bottom: 2px solid #000;
            margin: 10px 0;
        }

        .section {
            margin-bottom: 15px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 11px;
        }

        .item {
            margin-bottom: 10px;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .item-name {
            flex: 1;
        }

        .item-price {
            text-align: right;
            white-space: nowrap;
            margin-left: 10px;
        }

        .addon {
            font-size: 10px;
            padding-left: 15px;
            margin: 2px 0;
            color: #333;
        }

        .addon-item {
            display: flex;
            justify-content: space-between;
        }

        .notes {
            font-size: 10px;
            font-style: italic;
            padding-left: 15px;
            margin-top: 3px;
            color: #666;
        }

        .total-section {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 2px dashed #000;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .total-row.grand {
            font-size: 14px;
            font-weight: bold;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #000;
        }

        .payment-section {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 2px dashed #000;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px dashed #000;
            font-size: 11px;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            
            .no-print {
                display: none !important;
            }
        }

        /* Print Button */
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-family: Arial, sans-serif;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 9999;
        }

        .print-button:hover {
            background: #0056b3;
        }

        .print-button i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <button class="print-button no-print" onclick="window.print()">
        🖨️ Cetak Struk
    </button>

    <!-- Receipt Content -->
    <div class="receipt-header">
        <div class="store-name">{{ $setting->store_name ?? 'NAMA TOKO' }}</div>
        <div class="store-info">
            @if($setting && $setting->store_address)
                {{ $setting->store_address }}<br>
            @endif
            @if($setting && $setting->store_phone)
                Telp: {{ $setting->store_phone }}<br>
            @endif
            @if($setting && $setting->store_email)
                Email: {{ $setting->store_email }}
            @endif
        </div>
    </div>

    <!-- Transaction Info -->
    <div class="section">
        <div class="info-row">
            <span>No Invoice</span>
            <span class="bold">{{ $transaction->invoice_code }}</span>
        </div>
        <div class="info-row">
            <span>Tanggal</span>
            <span>{{ date('d/m/Y H:i', strtotime($transaction->created_at)) }}</span>
        </div>
        <div class="info-row">
            <span>Kasir</span>
            <span>{{ $transaction->user->name ?? 'Admin' }}</span>
        </div>
        @if($transaction->table_number)
        <div class="info-row">
            <span>No. Meja</span>
            <span class="bold">{{ $transaction->table_number }}</span>
        </div>
        @endif
    </div>

    <div class="divider-solid"></div>

    <!-- Items List -->
    <div class="section">
        @foreach($transaction->details as $detail)
            <div class="item">
                <div class="item-header">
                    <span class="item-name">
                        {{ $detail->product ? $detail->product->name : 'Produk Terhapus' }}
                    </span>
                </div>
                
                <div class="info-row">
                    <span>{{ $detail->qty }} x Rp {{ number_format($detail->sell_price, 0, ',', '.') }}</span>
                    <span class="bold">Rp {{ number_format($detail->sell_price * $detail->qty, 0, ',', '.') }}</span>
                </div>

                @php
                    $mods = $detail->modifiers_data ?? [];
                    $totalModsPrice = 0;
                @endphp

                @if(!empty($mods) && is_array($mods))
                    <div class="addon">
                        <div style="margin-bottom: 3px; font-weight: bold;">+ Add-ons:</div>
                        @foreach($mods as $mod)
                            @php 
                                $mName = is_array($mod) ? ($mod['name'] ?? '-') : ($mod->name ?? '-');
                                $mPrice = is_array($mod) ? ($mod['price'] ?? 0) : ($mod->price ?? 0);
                                $totalModsPrice += $mPrice * $detail->qty;
                            @endphp
                            <div class="addon-item">
                                <span>• {{ $mName }}</span>
                                @if($mPrice > 0)
                                    <span>+Rp {{ number_format($mPrice * $detail->qty, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($detail->notes)
                    <div class="notes">
                        ✏️ Catatan: {{ $detail->notes }}
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Total Section -->
    <div class="total-section">
        <div class="total-row">
            <span>Subtotal</span>
            <span>Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span>
        </div>

        @if($transaction->discount_amount > 0)
        <div class="total-row">
            <span>Diskon</span>
            <span>- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
        </div>
        @endif

        @if($transaction->tax_amount > 0)
        <div class="total-row">
            <span>Pajak ({{ $setting->tax_rate ?? 10 }}%)</span>
            <span>Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</span>
        </div>
        @endif

        <div class="total-row grand">
            <span>TOTAL BAYAR</span>
            <span>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Payment Info -->
    <div class="payment-section">
        <div class="total-row">
            <span>Metode Bayar</span>
            <span class="bold">{{ strtoupper($transaction->payment_method) }}</span>
        </div>

        @if($transaction->payment_method == 'cash')
        <div class="total-row">
            <span>Uang Tunai</span>
            <span>Rp {{ number_format($transaction->cash_amount, 0, ',', '.') }}</span>
        </div>
        <div class="total-row">
            <span>Kembalian</span>
            <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
        </div>
        @endif

        @if($transaction->payment_ref)
        <div class="total-row">
            <span>Ref. Number</span>
            <span>{{ $transaction->payment_ref }}</span>
        </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="center bold" style="margin-bottom: 10px;">
            *** TERIMA KASIH ***
        </div>
        <div class="center" style="font-size: 10px;">
            Barang yang sudah dibeli<br>
            tidak dapat dikembalikan
        </div>
        <div class="center" style="margin-top: 10px; font-size: 10px;">
            Powered by POS System
        </div>
    </div>

    <script>
        // Auto print saat halaman dimuat (opsional)
        // window.onload = function() {
        //     setTimeout(function() {
        //         window.print();
        //     }, 500);
        // }

        // Print dan tutup setelah print
        window.onafterprint = function() {
            // window.close(); // Uncomment jika ingin auto close
        }
    </script>
</body>
</html>