<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            margin: 5 px 0;

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
            cursor: progress;
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

    <button class="print-btn" onclick="printBill()"></button>
    <div class="center bold">
        YOUR EGG SHOP
    </div>
    <div class="center">
        mobile: xxxxxxxxxx
    </div>
    <div class="div">
        Bill No: {{ $purchaseinvoice->inv_number }}
        Date:{{ $purcheinvoice->invoice_date }}
        Supplier:{{ $purcheinvoice->supplier_name }}
    </div>
    <hr>
    <table>
        <thead>
            <tr>
                <td class="bold">Item</td>
                <td class="right bold">QTY</td>
                <td class="right bold">Price</td>
                <td class="right bold">Amt</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($item as $i)
                <tr>

                    <td>{{ $i->product_name }}
                        <small>{{ $i->size }}{{ $i->color }}</small>
                    </td>
                    <td class="right">{{ $i->quantity }}</td>
                    <td class="right">{{ $i->purchase_price }}</td>
                    <td class="right">{{ $i->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @if ($purchaseinvoice->tray_nedd == 'yes')
        <div class="">
            Tray Charge : ₹{{ $item->tray_use * 10 }}
        </div>
</body>

</html>
