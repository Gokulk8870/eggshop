@extends('layouts.master')
@section('title', 'Edit SalesInvoice')
@section('content')

    @if ($errors->any())

        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @php
        $item = $invoice->items->first();
    @endphp

    <div class="card">
        <div class="card-body">

            <form action="{{ route('salesinvoices.update', $invoice->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- 🔹 BASIC INFO -->

                <div class="row">
                    <div class="col-md-4">
                        <label>Customer Phone</label>
                        <input type="text" id="phno" class="form-control" name="phno" value="{{ $invoice->phno }}">
                    </div>


                    <div class="col-md-4">
                        <label>Customer Name</label>
                        <input type="text" id="customer_name" class="form-control" name="customer_name"
                            value="{{ $invoice->customer_name }}">
                    </div>

                    <div class="col-md-4">
                        <label>Date</label>
                        <input type="date" name="invoice_date" class="form-control" value="{{ $invoice->invoice_date }}">
                    </div>


                </div>

                <div class="row mt-3">
                    <div class="col-md-4">
                        <label>Invoice No</label>
                        <input type="text" name="inv_number" class="form-control" value="{{ $invoice->inv_number }}"
                            readonly>
                    </div>


                    <div class="col-md-4">
                        <label>Payment Method</label>
                        <select class="form-control" name="payment_method" id="payment_method">
                            <option value="">Select</option>
                            <option value="CASH" {{ $invoice->payment_method == 'CASH' ? 'selected' : '' }}>Cash</option>
                            <option value="UPI" {{ $invoice->payment_method == 'UPI' ? 'selected' : '' }}>UPI</option>
                        </select>
                    </div>


                </div>

                <!-- 🔹 TRAY -->

                <div class="row mt-3">
                    <div class="col-md-4">
                        <label>Tray Need</label><br>
                        <input type="hidden" name="tray_need" value="no">
                        <input type="checkbox" id="tray_need" name="tray_need" value="yes"
                            {{ $invoice->tray_need == 'yes' ? 'checked' : '' }}> Yes
                    </div>


                    <div class="col-md-4" id="seltray">
                        <label>Tray Color</label>
                        <select class="form-control" name="tray_id" id="tray">
                            <option value="">Select Tray</option>
                            @foreach ($trays as $tray)
                                <option value="{{ $tray->id }}" {{ $invoice->tray_id == $tray->id ? 'selected' : '' }}>
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
                                        <option value="{{ $product->id }}"
                                            {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                            {{ $product->product_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input type="text" id="eggprice" class="form-control" value="{{ $item->sale_price }}">
                            </td>

                            <td>
                                <input type="text" id="sale_price" name="sale_price" class="form-control"
                                    value="{{ $item->sale_price }}">
                                <input type="hidden" name="purchase_price" id="purchase_price"
                                    value="{{ $item->purchase_price }}">
                            </td>

                            <td>
                                <input type="number" id="quantity" name="quantity" class="form-control"
                                    value="{{ $item->quantity }}">
                            </td>

                            <td>
                                <input type="number" id="egg_quantity" name="eggs" class="form-control"
                                    value="{{ $item->eggs }}" readonly>
                            </td>

                            <td>
                                <input type="text" id="price" class="form-control" readonly>
                            </td>
                        </tr>
                    </tbody>

                    <tfoot>
                        <tr>
                            <th>Tray (+20)</th>
                            <td colspan="2">
                                <input type="number" id="tray_quantity" name="tray_quantity" class="form-control"
                                    value="{{ $item->tray_use }}">
                            </td>
                            <th colspan="2">Total</th>
                            <td>
                                <input type="text" id="total_price" name="total_price" class="form-control"
                                    value="{{ $invoice->total_price }}" readonly>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="5">Scan and Pay</td>
                            <td>
                                <div id="qrcode"></div>
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <!-- SNAPSHOT -->

                <input type="hidden" name="product_name" id="product_name" value="{{ $item->product_name }}">
                <input type="hidden" name="size" id="size" value="{{ $item->size }}">
                <input type="hidden" name="color" id="color" value="{{ $item->color }}">

                <button class="btn btn-success mt-3">Update</button>

            </form>
        </div>
    </div>

@endsection

@section('js')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        $(document).ready(function() {

            // CUSTOMER AUTO
            $('#phno').keyup(function() {
                let phno = $(this).val();
                if (phno != '') {
                    $.get('/cussearch', {
                        phno: phno
                    }, function(res) {
                        $('#customer_name').val(res ? res.name : '');
                    });
                }
            });

            // TRAY TOGGLE
            $('#seltray').toggle($('#tray_need').is(':checked'));
            $('#tray_quantity').toggle($('#tray_need').is(':checked'));

            $('#tray_need').change(function() {
                $('#seltray').toggle(this.checked);
                $('#tray_quantity').toggle(this.checked);
            });

            // PRODUCT CHANGE
            $('#product_id').change(function() {
                let id = $(this).val();
                if (id) {
                    $.get("{{ route('products.getproducts') }}", {
                        id: id
                    }, function(data) {
                        $('#eggprice').val(data.sale_price);
                        $('#sale_price').val(data.sale_price);
                        $('#purchase_price').val(data.purchase_price);

                        $('#product_name').val(data.product_name);
                        $('#size').val(data.size);
                        $('#color').val(data.color);
                    });
                }
            });

            // CALCULATION
            $('#quantity,#tray_quantity,#sale_price').on('keyup change', function() {
                calculate();
            });

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

                if ($('#payment_method').val() === 'UPI') {
                    generateQR(total);
                }
            }

            // PAYMENT CHANGE
            $('#payment_method').change(function() {
                let total = parseFloat($('#total_price').val()) || 0;
                if ($(this).val() === 'UPI') {
                    generateQR(total);
                } else {
                    $('#qrcode').html('');
                }
            });

            // QR
            function generateQR(amount) {
                if (amount <= 0) return;

                $.get('/generate-upi', {
                    amount: amount
                }, function(res) {
                    $('#qrcode').html('');
                    new QRCode(document.getElementById("qrcode"), {
                        text: res.upi,
                        width: 120,
                        height: 120
                    });
                });
            }

            // INITIAL RUN
            calculate();

        });
    </script>

@endsection
