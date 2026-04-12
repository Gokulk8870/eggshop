@extends('layouts.master')

@section('title', 'Create Customer')
@section('content')
    <div class="card">
        <div class="card-body">

            <form action="{{ route('customers.store') }}" method="POST">

                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cname">Customer Name</label>
                            <input type="text" name="cname" class="form-control" id="cname"
                                placeholder="Enter Customer Name">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phno">Phone Number</label>
                            <input type="text" name="phno" id="phno" class="form-control"
                                placeholder="Enter Phone Number">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="addr">Address</label>
                            <textarea name="addr" id="addr" class="form-control" placeholder="Enter Address"></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="active">❤️ active</option>
                                <option value="inactive">💔 inactive</option>
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
                        <i class="fas fa-save"></i> Save Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
