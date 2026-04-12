@extends('layouts.master')
@section('title', 'Create Supplier')
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
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="from-group">
                            <label for="name">Supplier Name</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="from-group">
                            <label for="phno">Phone Number</label>
                            <input type="text" name="phno" id="phno" value="{{ old('phno') }}"
                                class="form-control">

                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="from-group">
                            <label for="email">E-mail</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="from-group">
                            <label for="addr">Address</label>
                            <textarea name="addr" id="addr" value="{{ old('addr') }}" class="form-control"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="from-group">
                            <label for="company">Company Name</label>
                            <input type="text" name='company_name' value=" {{ old('company_name') }}"
                                class="form-control">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="from-group">
                            <label for="status">Status</label>
                            <select name="status" class="form-control">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>❤️ Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>💔 Inactive
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="text-right mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Save Supplier
                    </button>
                </div>

            </form>
        </div>
    </div>


@endsection
