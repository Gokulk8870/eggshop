@extends('layouts.master')
@section('title', 'Create Product')
@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('warning'))
        <div class="alert alert-warning">
            {{ session('warning') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">

            <form action="{{ route('products.store') }}" method="POST" id="productForm">
                @csrf

                <div class="row">

                    <!-- Product Name -->
                    <div class="col-md-4">
                        <label>Product Name *</label>
                        <input type="text" name="product_name" class="form-control" required>
                    </div>

                    <!-- Color -->
                    <div class="col-md-4">
                        <label>Color *</label>
                        <select name="color" class="form-control" required>
                            <option value="">Select Color</option>
                            <option value="white">White</option>
                            <option value="brown">Brown</option>
                        </select>
                    </div>

                    <!-- Size -->
                    <div class="col-md-4">
                        <label>Size *</label>
                        <select name="size" class="form-control" required>
                            <option value="">Select Size</option>
                            <option value="small">Small</option>
                            <option value="medium">Medium</option>
                            <option value="large">Large</option>
                        </select>
                    </div>

                </div>

                <div class="row mt-3">

                    <!-- Purchase Price -->
                    <div class="col-md-4">
                        <label>Purchase Price *</label>
                        <input type="number" step="0.01" name="purchase_price" class="form-control" required>
                    </div>

                    <!-- Sale Price -->
                    <div class="col-md-4">
                        <label>Sale Price *</label>
                        <input type="number" step="0.01" id="sale_price" name="sale_price" class="form-control"
                            required>
                    </div>

                    <!-- Quantity -->
                    <div class="col-md-4">
                        <label>Quantity (Trays) *</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1"
                            class="form-control" required>
                    </div>

                </div>

                <div class="row mt-3">

                    <!-- Tray Dropdown -->
                    <div class="col-md-4">
                        <label>Tray *</label>
                        <select name="tray_color" id="tray_id" class="form-control" required>
                            <option value="">-- Select Tray --</option>
                            @foreach ($trays as $tray)
                                <option value="{{ $tray->tcolor }}" data-quantity="{{ $tray->quantity }}">
                                    {{ $tray->tcolor }} (Available: {{ $tray->quantity }})
                                </option>
                            @endforeach
                        </select>
                        <small id="trayStockInfo"></small>
                    </div>

                    <!-- Egg Price -->
                    <div class="col-md-4">
                        <label>Egg Price (Per Egg)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">₹</span>
                            </div>
                            <input type="text" id="eggprice" name="eggprice" value="0.00" class="form-control"
                                readonly>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <label>Status *</label>
                        <select name="status" class="form-control" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                </div>

                <div class="row mt-3">

                    <!-- Total Eggs -->
                    <div class="col-md-4">
                        <label>Total Eggs</label>
                        <input type="text" id="totalegg" class="form-control" readonly>
                        <small class="text-muted">30 eggs per tray</small>
                    </div>

                </div>

                <div class="text-right mt-3">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success" id="submitBtn">
                        Save Product
                    </button>
                </div>

            </form>

        </div>
    </div>

@stop

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const salePrice = document.getElementById('sale_price');
            const eggPrice = document.getElementById('eggprice');
            const quantity = document.getElementById('quantity');
            const totalEgg = document.getElementById('totalegg');
            const traySelect = document.getElementById('tray_id');
            const trayStockInfo = document.getElementById('trayStockInfo');
            const submitBtn = document.getElementById('submitBtn');

            const EGGS_PER_TRAY = 30;

            // Egg price calculation
            function updateEggPrice() {
                let price = parseFloat(salePrice.value) || 0;
                eggPrice.value = (price / EGGS_PER_TRAY).toFixed(2);
            }

            // Total eggs
            function updateTotalEggs() {
                let qty = parseInt(quantity.value) || 0;
                totalEgg.value = qty * EGGS_PER_TRAY;
            }

            // Stock validation
            function validateStock() {
                const selected = traySelect.options[traySelect.selectedIndex];

                if (!selected || !selected.value) {
                    trayStockInfo.innerHTML = "<span class='text-warning'>Select tray</span>";
                    submitBtn.disabled = true;
                    return;
                }

                let available = parseInt(selected.dataset.quantity) || 0;
                let requested = parseInt(quantity.value) || 0;

                if (requested > available) {
                    trayStockInfo.innerHTML =
                        "<span class='text-danger'>Only " + available + " trays available</span>";
                    submitBtn.disabled = true;
                } else {
                    trayStockInfo.innerHTML =
                        "<span class='text-success'>" + available + " trays available</span>";
                    submitBtn.disabled = false;
                }
            }

            salePrice.addEventListener('input', updateEggPrice);

            quantity.addEventListener('input', function() {
                updateTotalEggs();
                validateStock();
            });

            traySelect.addEventListener('change', validateStock);

            updateEggPrice();
            updateTotalEggs();
        });
    </script>
@endpush
