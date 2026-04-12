@extends('layouts.master')
@section('title', 'Edit Product')
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

    <div class="card">
        <div class="card-body">

            <form action="{{ route('products.update', $product->id) }}" method="POST" id="productForm">
                @csrf
                @method('PUT')

                <div class="row">

                    <!-- Product Name -->
                    <div class="col-md-4">
                        <label>Product Name *</label>
                        <input type="text" name="product_name" class="form-control"
                            value="{{ old('product_name', $product->product_name) }}" required>
                    </div>

                    <!-- Color -->
                    <div class="col-md-4">
                        <label>Color *</label>
                        <select name="color" class="form-control" required>
                            <option value="">Select</option>
                            <option value="white" {{ $product->color == 'white' ? 'selected' : '' }}>White</option>
                            <option value="brown" {{ $product->color == 'brown' ? 'selected' : '' }}>Brown</option>
                        </select>
                    </div>

                    <!-- Size -->
                    <div class="col-md-4">
                        <label>Size *</label>
                        <select name="size" class="form-control" required>
                            <option value="small" {{ $product->size == 'small' ? 'selected' : '' }}>Small</option>
                            <option value="medium" {{ $product->size == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="large" {{ $product->size == 'large' ? 'selected' : '' }}>Large</option>
                        </select>
                    </div>

                </div>

                <div class="row mt-3">

                    <!-- Purchase Price -->
                    <div class="col-md-4">
                        <label>Purchase Price *</label>
                        <input type="number" step="0.01" name="purchase_price"
                            value="{{ old('purchase_price', $product->purchase_price) }}" class="form-control" required>
                    </div>

                    <!-- Sale Price -->
                    <div class="col-md-4">
                        <label>Sale Price *</label>
                        <input type="number" step="0.01" id="sale_price" name="sale_price"
                            value="{{ old('sale_price', $product->sale_price) }}" class="form-control" required>
                    </div>

                    <!-- Quantity -->
                    <div class="col-md-4">
                        <label>Quantity (Trays) *</label>
                        <input type="number" id="quantity" name="quantity"
                            value="{{ old('quantity', $product->quantity) }}" min="1" class="form-control" required>
                    </div>

                </div>

                <div class="row mt-3">

                    <!-- Tray -->
                    <div class="col-md-4">
                        <label>Tray *</label>
                        <select name="tray_color" id="tray_id" class="form-control" required>
                            <option value="">-- Select Tray --</option>
                            @foreach ($trays as $tray)
                                <option value="{{ $tray->tcolor }}" data-quantity="{{ $tray->quantity }}"
                                    {{ old('tray_color', $product->tray_color) == $tray->tcolor ? 'selected' : '' }}>

                                    {{ $tray->tcolor }} (Available: {{ $tray->quantity }})
                                </option>
                            @endforeach
                        </select>
                        <small id="trayStockInfo"></small>
                    </div>

                    <!-- Egg Price -->
                    <div class="col-md-4">
                        <label>Egg Price (Per Egg)</label>
                        <input type="text" id="eggprice" value="{{ number_format($product->sale_price / 30, 2) }}"
                            class="form-control" readonly>
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <label>Status *</label>
                        <select name="status" class="form-control" required>
                            <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                    </div>

                </div>

                <div class="row mt-3">

                    <!-- Total Eggs -->
                    <div class="col-md-4">
                        <label>Total Eggs</label>
                        <input type="text" id="totalegg" value="{{ $product->quantity * 30 }}" class="form-control"
                            readonly>
                        <small class="text-muted">30 eggs per tray</small>
                    </div>

                </div>

                <div class="text-right mt-3">
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        Update Product
                    </button>
                </div>

            </form>

        </div>
    </div>

@stop

@section('js')
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

            function updateEggPrice() {
                let price = parseFloat(salePrice.value) || 0;
                eggPrice.value = (price / EGGS_PER_TRAY).toFixed(2);
            }

            function updateTotalEggs() {
                let qty = parseInt(quantity.value) || 0;
                totalEgg.value = qty * EGGS_PER_TRAY;
            }

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
@stop
