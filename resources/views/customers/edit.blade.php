@extends('layouts.master')

@section('title', 'Edit Customer')
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('customers.update', $customer->id) }}" method="POST">
                @method('PUT')
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cname">Customer Name</label>
                            <input type="text" name="cname" class="form-control" id="cname"
                                value="{{ $customer->name }}" placeholder="Enter Customer Name">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phno">Phone Number</label>
                            <input type="text" name="phno" id="phno" class="form-control"
                                value="{{ $customer->phno }}" placeholder="Enter Phone Number">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="addr">Address</label>
                            <textarea name="addr" id="addr" class="form-control" placeholder="Enter Address">{{ $customer->addr }}</textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="active" {{ $customer->status == 'active' ? 'selected' : '' }}>❤️ active
                                </option>
                                <option value="inactive" {{ $customer->status == 'inactive' ? 'selected' : '' }}>💔 inactive
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">
                            Submit
                    </button>
                </div> -->

                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-update"></i> Update Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
