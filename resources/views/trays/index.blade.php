@extends('layouts.master')
@section('title', 'Manage Trays')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
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
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tray Color</label>

                        <input type="text" name="tray_color" id="tray_color" class="form-control" list="tray_list"
                            placeholder="Search Tray Color">

                        <datalist id="tray_list">
                            @foreach ($trays as $tray)
                                <option value="{{ $tray->tcolor }}"></option>
                            @endforeach
                        </datalist>

                    </div>
                </div>
            </div>
            <div class="row">
                @if (auth()->user()->role == 'admin')
                    <div class="col-md-12 d-flex justify-content-end align-items-end">
                        <a href="{{ route('trays.create') }}" class="btn btn-primary">Add Tray</a>
                    </div>
                @endif
            </div>

            <hr>

            <table class="table table-bordered" id="traytable">

                <thead class="table-info">
                    <tr>
                        <th>S.No</th>
                        <th>Tray Color</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($trays as $tray)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $tray->tcolor }}</td>
                            <td>{{ $tray->quantity }}</td>
                            <td>
                                @if (auth()->user()->role == 'admin')
                                    <a href="{{ route('trays.show', $tray->id) }}">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('trays.edit', $tray->id) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('trays.destroy', $tray->id) }}" method="POST" class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-primary"
                                            onclick="return confirm('Delete this tray?')">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </form>
                                @endif
                            </td>

                        </tr>
                    @endforeach

                </tbody>

            </table>

        </div>
    </div>

@endsection


@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#traytable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true
            });

            $('#tcolor').keyup(function() {

                let tcolor = $(this).val();

                if (tcolor != "") {

                    $.ajax({

                        url: "{{ route('trays.search') }}",

                        method: "GET",

                        data: {
                            tcolor: tcolor
                        },

                        success: function(data) {

                            let html = "";

                            data.forEach(function(tray) {

                                html += '<li class="list-group-item tray-item">' + tray
                                    .tcolor + '</li>';

                            });

                            $('.tray_list').html(html);

                        }

                    });

                } else {

                    $('.tray_list').html('');

                }

            });


            $(document).on('click', '.tray-item', function() {

                $('#tcolor').val($(this).text());

                $('.tray_list').html('');

            });

        });
    </script>
@endpush
