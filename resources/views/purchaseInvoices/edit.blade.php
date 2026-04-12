@extends('layouts.master')
@section('title', 'Edit Purchase Invoice')
@section('content')
    {{-- Debug: Check if $purchaseInvoice exists --}}

    @if (!isset($purchaseInvoice))
        <div class="alert alert-danger">
            Purchase Invoice data is missing!
        </div>
    @else
        <div class="alert alert-info">
            Invoice ID: {{ $purchaseInvoice->id }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('purchaseinvoices.update', ['purchaseinvoice' => $purchaseInvoice->id]) }}" method="post">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Supplier Phone</label>
                        <input type="text" id="phno" class="form-control" name="phno"
                            value="{{ $purchaseInvoice->phno }}">
                    </div>

                    <div class="col-md-4">
                        <label for="">Supplier Name</label>
                        <input type="text" value="{{ $purchaseInvoice->supplier_name }}" id="supplier_name"
                            class="form-control" name="supplier_name">
                    </div>

                    <div class="col-md-4">
                        <label>Date</label>
                        <input type="date" name="invoice_date" class="form-control"
                            value="{{ $purchaseInvoice->invoice_date }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Invoice No</label>
                        <input type="text" name="inv_number" class="form-control"
                            value="{{ $purchaseInvoice->inv_number }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label for="">Payment Method</label>
                        <select class="form-control" name="payment_method" id="payment_method">
                            <option value="">Select</option>
                            <option value="CASH" {{ $purchaseInvoice->payment_method == 'CASH' ? 'selected' : '' }}>CASH
                            </option>
                            <option value="UPI" {{ $purchaseInvoice->payment_method == 'UPI' ? 'selected' : '' }}>UPI
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mt-4">
                            <label for="">Tray Need</label>
                            <input type="hidden" name="tray_need" value="no">
                            <input type="checkbox" id="tray_need" name="tray_need" value="yes"
                                {{ old('tray_need', $purchaseInvoice->tray_need ?? 'no') == 'yes' ? 'checked' : '' }}>
                            Yes
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <label for="">Tray Color</label>
                        <select class="form-control" name="tray_id" id="tray">
                            @foreach ($trays as $tray)
                                <option value="{{ $tray->id }}"
                                    {{ old('tray_id', $purchaseInvoice->tray_id) == $tray->id ? 'selected' : '' }}>
                                    {{ $tray->tcolor }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Purchase Price</th>
                            <th>Qty (tray)</th>
                            <th>Egg Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>

                                <select name="product_id" id="product_id" class="form-control">
                                    <option value="">---</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ optional($item)->product_id == $product->id ? 'selected' : '' }}>
                                            {{ $product->product_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" id="eggprice" class="form-control"
                                    value="{{ $item->product->eggprice ?? '' }}">
                            </td>
                            <td>
                                <input type="text" id="purchase_price" name="purchase_price" class="form-control"
                                    value="{{ $item->product->purchase_price ?? '' }}">
                            </td>
                            <td>
                                <input type="number" id="quantity" name="quantity" class="form-control"
                                    value="{{ $item->quantity ?? '' }}">
                            </td>
                            <td>
                                <input type="number" id="egg_quantity" name="eggs" value="{{ $item->eggs ?? 0 }}"
                                    class="form-control" readonly>
                            </td>
                            <td>
                                <input type="text" id="price" name="price" class="form-control">
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Tray (+10)</th>
                            <td colspan="2">
                                <input type="number" id="tray_quantity" name="tray_quantity" class="form-control"
                                    value="{{ $item->tray_use ?? 0 }}">
                            </td>
                            <th colspan="2">Total</th>
                            <td>
                                <input type="text" id="total_price" name="total_price" class="form-control"
                                    value="{{ $purchaseInvoice->total_price }}" readonly>
                            </td>

                        </tr>
                        <tr>
                            <td colspan="5">
                                <div class="col-md-4" id="qr_section">
                                    <label>QR Code</label>
                                </div>
                            </td>
                            <td>
                                <div id="qrcode"></div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div class="btn">
                    <button class="btn btn-success mt-3" type="submit">Update</button>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        $(document).ready(function() {

            // 🔍 Auto fill supplier name
            $('#phno').keyup(function() {
                let phno = $(this).val();

                if (phno != '') {
                    $.ajax({
                        url: "/supsearch",
                        method: "GET",
                        data: {
                            phno: phno
                        },
                        success: function(response) {
                            $('#supplier_name').val(response ? response.name : '');
                        }
                    });
                } else {
                    $('#supplier_name').val('');
                }
            });

            // 🔒 Initial UI setup
            $('#qr_section').hide();

            if ($('#tray_need').is(':checked')) {
                $('#tray').prop('disabled', false);
                $('#tray_quantity').prop('disabled', false);
            } else {
                $('#tray').prop('disabled', true);
                $('#tray_quantity').prop('disabled', true);
            }

            // 💳 Payment method change
            $('#payment_method').change(function() {
                if ($(this).val() == 'UPI') {
                    $('#qr_section').show();
                    updateTotal();
                } else {
                    $('#qr_section').hide();
                    $('#qrcode').html('');
                }
            });

            // 🧺 Tray checkbox toggle
            $('#tray_need').change(function() {
                if ($(this).is(':checked')) {
                    $('#tray').prop('disabled', false);
                    $('#tray_quantity').prop('disabled', false);
                } else {
                    $('#tray').prop('disabled', true);
                    $('#tray_quantity').prop('disabled', true);
                    $('#tray_quantity').val(0);
                }
                updateTotal();
            });

            // 🔄 Recalculate on change
            $('#quantity, #tray_quantity, #purchase_price').on('keyup change', function() {
                updateTotal();
            });

            // 📦 Product price fetch
            $('select[name="product_id"]').on('change', function() {
                let id = $(this).val();

                $.ajax({
                    url: '/get-price/' + id,
                    type: 'GET',
                    success: function(res) {
                        $('input[name="purchase_price"]').val(res.price);
                        updateTotal(); // ✅ recalc after price load
                    }
                });
            });

            // 🔢 MAIN CALCULATION FUNCTION
            function updateTotal() {

                let qty = parseFloat($('#quantity').val()) || 0;
                let purchase = parseFloat($('#purchase_price').val()) || 0;
                let trayQty = parseFloat($('#tray_quantity').val()) || 0;

                // product total
                let productTotal = qty * purchase;

                // tray total (only if checked)
                let trayTotal = $('#tray_need').is(':checked') ? trayQty * 10 : 0;

                let total = productTotal + trayTotal;

                // update fields
                $('#price').val(productTotal.toFixed(2));
                $('#egg_quantity').val(qty * 30);
                $('#total_price').val(total.toFixed(2));

                // generate QR if UPI
                if ($('#payment_method').val() == 'UPI') {
                    generateQR(total);
                }
            }

            // 📱 QR Generator
            function generateQR(amount) {

                if (amount <= 0) return;

                $.get('/generate-upi', {
                    amount: amount
                }, function(response) {

                    let upi = response.upi;

                    $('#qrcode').html('');

                    new QRCode(document.getElementById("qrcode"), {
                        text: upi,
                        width: 120,
                        height: 120
                    });

                });
            }

            // 🚀 IMPORTANT: RUN ON PAGE LOAD
            updateTotal(); // ✅ THIS FIXES EVERYTHING

        });
    </script>
@endsection
