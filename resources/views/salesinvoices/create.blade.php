@extends('layouts.master')
@section('title', 'Create SalesInvoice')
@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">

            <form action="{{ route('salesinvoices.store') }}" method="POST">
                @csrf

                <!-- 🔹 BASIC INFO -->
                <div class="row">
                    <div class="col-md-4">
                        <label>Customer Phone</label>
                        <input type="text" id="phno" class="form-control" name="phno">
                    </div>

                    <div class="col-md-4">
                        <label>Customer Name</label>
                        <input type="text" id="customer_name" class="form-control" name="customer_name">
                    </div>

                    <div class="col-md-4">
                        <label>Date</label>
                        <input type="date" name="invoice_date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <label>Invoice No</label>
                        <input type="text" name="inv_number" class="form-control" value="{{ $invoice_number }}" readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Payment Method</label>
                        <select class="form-control" name="payment_method" id="payment_method">
                            <option value="">Select</option>
                            <option value="CASH">Cash</option>
                            <option value="UPI">UPI</option>
                        </select>
                    </div>
                </div>

                <!-- 🔹 TRAY -->
                <div class="row mt-3">
                    <div class="col-md-4">
                        <label>Tray Need</label><br>
                        <input type="hidden" name="tray_need" value="no">
                        <input type="checkbox" id="tray_need" name="tray_need" value="yes"> Yes
                    </div>

                    <div class="col-md-4" id="seltray">
                        <label>Tray Color</label>
                        <select class="form-control" name="tray_id" id="tray">
                            <option value="">Select Tray</option>
                            @foreach ($trays as $tray)
                                <option value="{{ $tray->id }}">
                                    {{ $tray->tcolor }} ({{ $tray->available }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <hr>

                <!-- 🔹 PRODUCT TABLE -->
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Sale Price</th>
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
                                        <option value="{{ $product->id }}">
                                            {{ $product->product_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td><input type="text" id="eggprice" class="form-control" readonly></td>

                            <td>
                                <input type="text" id="sale_price" name="sale_price" class="form-control">
                                <input type="hidden" name="purchase_price" id="purchase_price">
                            </td>

                            <td><input type="number" id="quantity" name="quantity" class="form-control"></td>

                            <td>
                                <input type="number" id="egg_quantity" name="eggs" class="form-control" readonly>
                            </td>

                            <td><input type="text" id="price" class="form-control" readonly></td>
                        </tr>
                    </tbody>

                    <tfoot>
                        <tr>
                            <th>Tray (+20)</th>
                            <td colspan="2">
                                <input type="number" id="tray_quantity" name="tray_quantity" class="form-control">
                            </td>
                            <th colspan="2">Total</th>
                            <td>
                                <input type="text" id="total_price" name="total_price" class="form-control" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5"> <label for="">Scan and Pay</label></td>
                            <td>
                                <div id="qrcode">

                                </div>
                            </td>

                        </tr>
                    </tfoot>
                </table>

                <!-- 🔥 SNAPSHOT HIDDEN -->
                <input type="hidden" name="product_name" id="product_name">
                <input type="hidden" name="size" id="size">
                <input type="hidden" name="color" id="color">

                <button class="btn btn-success mt-3">Save</button>

            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        $(document).ready(function() {

            // 🔹 Customer auto fill
            $('#phno').keyup(function() {
                let phno = $(this).val();

                if (phno !== '') {
                    $.get('/cussearch', {
                        phno: phno
                    }, function(res) {
                        $('#customer_name').val(res ? res.name : '');
                    });
                }
            });

            // 🔹 Tray toggle
            $('#seltray').hide();
            $('#tray_quantity').hide();

            $('#tray_need').change(function() {
                if ($(this).is(':checked')) {
                    $('#seltray').show();
                    $('#tray_quantity').show();
                } else {
                    $('#seltray').hide();
                    $('#tray_quantity').hide();
                }
            });

            // 🔹 Product select
            $('#product_id').change(function() {

                let id = $(this).val();

                if (id) {
                    $.get("{{ route('products.getproducts') }}", {
                        id: id
                    }, function(data) {

                        $('#eggprice').val(data.sale_price);
                        $('#sale_price').val(data.sale_price);
                        $('#purchase_price').val(data.purchase_price);

                        // 🔥 snapshot
                        $('#product_name').val(data.product_name);
                        $('#size').val(data.size);
                        $('#color').val(data.color);

                    });
                }
            });

            // 🔹 Auto calculation trigger
            $('#quantity, #tray_quantity, #sale_price').on('keyup change', function() {
                calculate();
            });

            // 🔥 MAIN CALCULATION FUNCTION
            function calculate() {

                let qty = parseFloat($('#quantity').val()) || 0;
                let sale = parseFloat($('#sale_price').val()) || 0;
                let trayQty = parseFloat($('#tray_quantity').val()) || 0;

                let productTotal = qty * sale;
                let trayTotal = trayQty * 20;
                let total = productTotal + trayTotal;

                $('#price').val(productTotal);
                $('#egg_quantity').val(qty * 30);
                $('#total_price').val(total);

                // 🔥 AUTO QR GENERATE
                if ($('#payment_method').val() === 'UPI') {
                    generateQR(total);
                }
            }

            // 🔥 PAYMENT METHOD CHANGE
            $('#payment_method').change(function() {

                let total = parseFloat($('#total_price').val()) || 0;

                if ($(this).val() === 'UPI') {
                    generateQR(total);
                } else {
                    $('#qrcode').html('');
                }
            });

            // 🔥 QR GENERATION FUNCTION
            function generateQR(amount) {

                if (amount <= 0) return;

                $.get('/generate-upi', {
                    amount: amount
                }, function(response) {

                    let upi = response.upi;

                    // clear old QR
                    $('#qrcode').html('');

                    // create new QR
                    new QRCode(document.getElementById("qrcode"), {
                        text: upi,
                        width: 120,
                        height: 120
                    });

                }).fail(function() {
                    alert("UPI generation failed");
                });
            }

        });
    </script>
@endpush
