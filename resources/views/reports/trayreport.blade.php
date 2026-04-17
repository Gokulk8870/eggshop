@extends('layouts.master')

@section('title', 'Tray Report')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
@endpush
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ url('reports/tray') }}" method="get">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="tcolor">Tray Color</label>
                            <input type="text" name="tcolor" value="{{ request('tcolor') }}" class="form-control"
                                list="traycolorlist">

                            <datalist id="traycolorlist">
                                @foreach ($trays as $color)
                                    <option value="{{ $color->tcolor }}">
                                @endforeach
                            </datalist>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex align-items-center mt-4">
                        <div class="form-group">
                            <button type="submit" class=" btn btn-primary"><i class="fas fa-filter"></i>
                                Filter</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="card bg-success text-white p-3">
                        <h6>Total Tray In</h6>
                        <h4>{{ $trays->sum('tray_in') }}</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-secondary text-white p-3">
                        <h6>Total Tray Out</h6>
                        <h4>{{ $trays->sum('tray_out') }}</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-primary text-white p-3">
                        <h6>Total Opening Tray</h6>
                        <h4>{{ $trays->sum('opening_tray') }}</h4>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white p-3">
                        <h6>Total Closing Tray</h6>
                        <h4>{{ $trays->sum('closing_tray') }}</h4>
                    </div>
                </div>
            </div>
            <table class="table table-bordered" id="trayreport">
                <thead class="table-info">
                    <tr>
                        <th>S.No</th>
                        <th>Tray Color</th>
                        <th>Tray In</th>
                        <th>Tray Out</th>
                        <th>Opening Tray</th>
                        <th>Close Tray</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($trays as $tray)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $tray->tcolor }}</td>
                            <td>{{ $tray->tray_in ?? 0 }}</td>
                            <td>{{ $tray->tray_out ?? 0 }}</td>
                            <td>{{ $tray->opening_tray ?? 0 }}</td>
                            <td>{{ $tray->closing_tray ?? 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!-- 2. DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    {{-- <!-- 3. Responsive -->
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script> --}}

    <!-- 4. Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

    <!-- 5. Dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script>
        $(document).ready(function() {
            $('#trayreport').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy',
                    'csv',
                    'excel',
                    'pdf',
                    'print',
                    'colvis'
                ],
            });
        });
    </script>
@endpush
