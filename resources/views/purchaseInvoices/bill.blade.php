<!DOCTYPE html>
<html>

<head>
    <title>Purchase Receipt</title>

    <style>
        @page {
            size: 80mm auto;
            margin: 0;
        }

        body {
            font-family: monospace;
            width: 80mm;
            margin: 0;
            padding: 5px;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        hr {
            border: none;
            border-top: 1px dashed black;
            margin: 5px 0;
        }

        table {
            width: 100%;
            font-size: 12px;
        }

        td {
            padding: 2px 0;
        }

        .print-btn {
            width: 100%;
            padding: 6px;
            margin-bottom: 8px;
            background: black;
            color: white;
            border: none;
            cursor: pointer;
        }

        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>

<body>

    <button class="print-btn" onclick="printBill()">🖨️ Print</button>

    <!-- SHOP -->
    <div class="center bold">YOUR EGG SHOP</div>
    <div class="center">Mobile: xxxxxxxxxx</div>

    <hr>

    <!-- BILL INFO -->
    <div>
        Bill No : {{ $purchaseinvoice->inv_number }} <br>
        Date : {{ $purchaseinvoice->invoice_date }} <br>
        Supplier : {{ $purchaseinvoice->supplier_name }}
    </div>

    <hr>

    <!-- ITEMS -->
    <table>
        <thead>
            <tr>
                <td class="bold">Item</td>
                <td class="right bold">Qty</td>
                <td class="right bold">Price</td>
                <td class="right bold">Amt</td>
            </tr>
        </thead>

        <tbody>
            @foreach ($purchaseinvoice->items as $i)
                <tr>
                    <td>
                        {{ $i->product->product_name }}<br>
                        <small>{{ $i->size }} {{ $i->color }}</small>
                    </td>
                    <td class="right">{{ $i->quantity }}</td>
                    <td class="right">{{ $i->purchase_price }}</td>
                    <td class="right">{{ $i->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <!-- 🧺 TRAY CALCULATION -->
    @php
        $trayTotal = 0;

        if ($purchaseinvoice->tray_need == 'yes') {
            $trayTotal = $purchaseinvoice->items->sum(fn($i) => $i->tray_use * 10);
        }
    @endphp

    @if ($purchaseinvoice->tray_need == 'yes')
        <div>
            Tray Charge: ₹ {{ $trayTotal }}
        </div>
    @endif

    <hr>

    <!-- 💰 TOTAL -->
    <div>
        Items Total: ₹ {{ $purchaseinvoice->total_price }}
    </div>

    <div class="bold">
        Grand Total: ₹ {{ $purchaseinvoice->total_price + $trayTotal }}
    </div>

    <hr>

    <!-- FOOTER -->
    <div class="center">
        Thank You 😊 <br>
        Visit Again
    </div>

    <script>
        function printBill() {
            window.print();
        }
    </script>

</body>

</html>
