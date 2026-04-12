@extends('layouts.master')
@section('title', 'Edit Supplier')
@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            session('success')
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
            <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="from-group">
                            <label for="name">Supplier Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ $supplier->name }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="from-group">
                            <label for="phno">Phone Number</label>
                            <input type="text" name="phno" id="phno" value="{{ $supplier->phno }}"
                                class="form-control">

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="from-group">
                            <label for="email">E-mail</label>
                            <input type="email" name="email" id="email" value="{{ $supplier->email }}"
                                class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="from-group">
                            <label for="addr">Address</label>
                            <textarea name="addr" id="addr" value="{{ old('addr') }}" class="form-control">{{ $supplier->addr }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="from-group">
                            <label for="company">Company Name</label>
                            <input type="text" name='company_name' value=" {{ $supplier->company_name }}"
                                class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="from-group">
                            <label for="status">Status</label>
                            <select name="status" class="form-control">
                                <option value="active" {{ $supplier->status == 'active' ? 'selected' : '' }}>❤️ Active
                                </option>
                                <option value="inactive" {{ $supplier->status == 'inactive' ? 'selected' : '' }}>💔 Inactive
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Update Supplier
                    </button>
                </div>

            </form>
        </div>
    </div>

@endsection
