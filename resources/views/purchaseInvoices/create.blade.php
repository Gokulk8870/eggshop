@extends('layouts.master')

@section('title', 'Purchase Invoice Create')
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <ul>
                    <li>{{ $error }}</li>
                </ul>
            @endforeach
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('purchaseinvoices.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="phno">Phone Number</label>
                            <input type="text" name="phno" class="form-control" id="phno"
                                value="{{ old('phno') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sname">Supplier Name</label>
                            <input type="text" id="sname" class="form-control" name="supplier_name">

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ino">Invoice No</label>
                            <input type="text" class="form-control" name="inv_number" id="ino"
                                value="{{ $invoice_number }}" readonly>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" class="form-control" name="invoice_date" id="date"
                                value="{{ date('Y-m-d') }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Payment Method</label>
                            <select class="form-control" name="payment_method" id="payment_method">
                                <option value="">Select</option>
                                <option value="CASH">Cash</option>
                                <option value="UPI">UPI</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label>Tray Need</label><br>
                        <input type="hidden" name="tray_need" value="no">
                        <input type="checkbox" id="tray_need" name="tray_need" value="yes"> Yes
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4" id="seltray">
                        <label>Tray Color</label>
                        <select class="form-control" name="tray_id" id="tray">
                            <option value="">Select Tray</option>
                            @foreach ($trays as $tray)
                                <option value="{{ $tray->id }}">{{ $tray->tcolor }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr>
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
                                        <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                    @endforeach
                                </select>
                            </td>

                            <td><input type="text" id="eggprice" name="eggprice" class="form-control"></td>
                            <td><input type="text" id="purchase_price" name="purchase_price" class="form-control"></td>
                            <td><input type="number" id="quantity" name="quantity" class="form-control"></td>
                            <td>
                                <input type="number" id="egg_quantity" name="eggs" class="form-control">
                            </td>

                            <td><input type="text" id="price" name="price" class="form-control"></td>
                        </tr>
                    </tbody>

                    <tfoot>
                        <tr>
                            <th>Tray (+10)</th>
                            <td colspan="2">
                                <input type="number" id="tray_quantity" name="tray_quantity" class="form-control">
                            </td>
                            <th colspan="2">Total</th>
                            <td><input type="text" id="total_price" name="total_price" class="form-control">
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
                <button class="btn btn-success mt-3">Save</button>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        $(document).ready(function() {
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
                            if (response) {
                                $('#sname').val(response.name);
                            } else {
                                $('#sname').val('');
                            }
                        }
                    });
                } else {
                    $('#sname').val('');
                }
            });


            $('#qr_section').hide();
            $('#seltray').hide();
            $('#tray_quantity').hide();
            $('#tray').prop('disabled', true);
            $('#tray_quantity').prop('disabled', true);


            $('#payment_method').change(function() {
                if ($(this).val() == 'UPI') {
                    $('#qr_section').show(); // ✅ FIXED
                    updateTotal();
                } else {
                    $('#qr_section').hide();
                    $('#qrcode').html(''); // ✅ FIXED
                }
            });

            $('#tray_need').change(function() {

                if ($(this).is(':checked')) {
                    $('#seltray').show();
                    $('#tray_quantity').show();
                    $('#tray').prop('disabled', false);
                    $('#tray_quantity').prop('disabled', false);
                } else {
                    $('#seltray').hide();
                    $('#tray_quantity').hide();
                    $('#tray').prop('disabled', true);
                    $('#tray_quantity').prop('disabled', true);
                }

            });
            $('#quantity, #tray_quantity').keyup(function() {
                updateTotal();
            });

            function updateTotal() {

                let qty = parseFloat($('#quantity').val()) || 0;
                let purchase = parseFloat($('#purchase_price').val()) || 0;
                let trayQty = parseFloat($('#tray_quantity').val()) || 0;

                let productTotal = qty * purchase; // ✅ FIXED
                let trayTotal = trayQty * 10;
                let total = productTotal + trayTotal;

                $('#price').val(productTotal);
                $('#egg_quantity').val(qty * 30);
                $('#total_price').val(total);

                if ($('#payment_method').val() == 'UPI') {
                    generateQR(total);
                }
            }

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

            $('select[name="product_id"]').on('change', function() {
                let id = $(this).val();

                $.ajax({
                    url: '/get-price/' + id,
                    type: 'GET',
                    success: function(res) {
                        console.log(res);
                        $('input[name="purchase_price"]').val(res.price);
                    }
                });
            });
        });
    </script>
@endpush
