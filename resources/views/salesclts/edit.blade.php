@extends('layouts.master')
@section('title', 'Edit Sales Control Page')
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('salesclts.update', $salesclt->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="prefix">Prefix</label>
                            <input type="text" name="prefix" value="{{ $salesclt->prefix }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="suffix">Suffix</label>
                            <input type="text" name="suffix" value="{{ $salesclt->suffix }}" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="year">Year</label>
                            <input type="text" name="year" value="{{ $salesclt->year }}" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="active">active</option>
                                <option value="inactive">inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
