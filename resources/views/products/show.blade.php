@extends('layouts.master')
@section('title', 'Show Product')
@section('content')

    <table class="table table-bordered">

        <tr>
            <th>Name</th>
            <td>{{ $product->product_name }}</td>
        </tr>

        <tr>
            <th>Size</th>
            <td>{{ $product->size }}</td>
        </tr>

        <tr>
            <th>Color</th>
            <td>{{ $product->color }}</td>
        </tr>
        <tr>
            <th>Egg Price</th>
            <td>{{ $product->eggprice }}</td>
        </tr>


        <tr>
            <th>Sale Price</th>
            <td>{{ $product->sale_price }}</td>
        </tr>
        <tr>
            <th>Purchase Price</th>
            <td>{{ $product->purchase_price }}</td>
        </tr>

        <tr>
            <th>Quantity</th>
            <td>{{ $product->quantity }}</td>
        </tr>
        <tr>
            <th>Total Eggs</th>
            <td>{{ $product->totaleggs }}</td>
        </tr>





    </table>

@stop
