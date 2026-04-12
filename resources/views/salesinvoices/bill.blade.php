<!DOCTYPE html>
<html>

<head>
    <title>Receipt</title>

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

            body {
                width: 80mm;
            }
        }
    </style>
</head>

<body>

    <!-- 🖨️ PRINT BUTTON -->
    <button class="print-btn" onclick="printBill()">🖨️ Print</button>

    <!-- 🏪 SHOP -->
    <div class="center bold">
        YOUR EGG SHOP
    </div>

    <div class="center">
        Mobile: xxxxxxxxxx
    </div>

    <hr>

    <!-- 📄 BILL INFO -->
    <div>
        Bill No : {{ $salesInvoice->inv_number }} <br>
        Date : {{ $salesInvoice->invoice_date }} <br>
        Customer: {{ $salesInvoice->customer_name }}
    </div>

    <hr>

    <!-- 🧾 ITEMS -->
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
            @foreach ($salesInvoiceItems as $item)
                <tr>
                    <!-- 🔥 SNAPSHOT DATA -->
                    <td>
                        {{ $item->product_name }}<br>
                        <small>{{ $item->size }} {{ $item->color }}</small>
                    </td>

                    <td class="right">{{ $item->quantity }}</td>

                    <td class="right">{{ $item->sale_price }}</td>

                    <td class="right">{{ $item->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <!-- 🧺 TRAY CHARGE -->
    @php
        $item = $salesInvoice->items->first();
    @endphp

    @if ($salesInvoice->tray_need == 'yes')
        <div>
            Tray Charge: ₹ {{ $item->tray_use * 20 }}
        </div>
    @endif



    <hr>

    <!-- 💰 TOTAL -->
    <div class="bold">
        Total: ₹ {{ $salesInvoice->total_price + $item->tray_use * 20 }}
    </div>

    <hr>

    <!-- 💬 FOOTER -->
    <div class="center">
        Thank You 😊 <br>
        Visit Again
    </div>

    <!-- 🔥 PRINT -->
    <script>
        function printBill() {
            window.print();
        }
    </script>

</body>

</html>
