@extends('layouts.master')

@section('content')
    <h2 style="font-size:22px;font-weight:600;margin-bottom:20px;">Welcome Dashboard 🥚</h2>
    <div class="card">
        <div class="card-body">
            <div class="row">
                @if (in_array(auth()->user()->role, ['admin', 'employee']))
                    <!-- Sales -->
                    <div class="col-md-3">
                        <div class="card bg-success text-white p-3">
                            <h5>Total Sales</h5>
                            <h3>₹ {{ number_format($totalSales) }}</h3>
                        </div>
                    </div>
                @endif


                @if (auth()->user()->role === 'admin')
                    <div class="col-md-3">
                        <div class="card bg-primary text-white p-3">
                            <h5>Total Purchase</h5>
                            <h3>₹ {{ number_format($totalPurchase) }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white p-3">
                            <h5>Total Expenses</h5>
                            <h3>₹ {{ number_format($totalExpenses) }}</h3>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-dark text-white p-3">
                            <h5>Total Profit</h5>
                            @if ($totalProfit < 0)
                                <h4 class="text-danger">
                                    🔻 Loss: ₹ {{ number_format(abs($totalProfit)) }}
                                </h4>
                            @else
                                <h4 class="text-success">
                                    📈 Profit: ₹ {{ number_format($totalProfit) }}
                                </h4>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
            <h4 class="fw-bold mt-3 mb-3">Quick Access</h4>
            <div class="row">
                @if (in_array(auth()->user()->role, ['admin', 'employee']))
                    <!-- Box 1 -->
                    <div class="col-md-3">
                        <div class="card text-center p-3">
                            <h5>Create Customer</h5>
                            <a href="{{ route('customers.create') }}" class="btn btn-primary btn-sm mt-2">
                                Open
                            </a>
                        </div>
                    </div>

                    <!-- Box 2 -->
                    <div class="col-md-3">
                        <div class="card text-center p-3">
                            <h5>Create Sales</h5>
                            <a href="{{ route('salesinvoices.create') }}" class="btn btn-primary btn-sm mt-2">
                                Open
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Box 3 -->
                @if (auth()->user()->role === 'admin')
                    <div class="col-md-3">
                        <div class="card text-center p-3">
                            <h5>Create Purchase</h5>
                            <a href="{{ route('purchaseinvoices.create') }}" class="btn btn-primary btn-sm mt-2">
                                Open
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center p-3">
                            <h5>Add Supplier</h5>
                            <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm mt-2">
                                Open
                            </a>
                        </div>
                    </div>
                @endif

            </div>

            <div class="row mt-3">
                @if (auth()->user()->role === 'admin')
                    <div class="col-md-3">
                        <div class="card text-center p-3">
                            <h5>Add Expense</h5>
                            <a href="{{ route('expenses.create') }}" class="btn btn-primary btn-sm mt-2">
                                Open
                            </a>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center p-3">
                            <h5>Add Tray</h5>
                            <a href="{{ route('trays.create') }}" class="btn btn-primary btn-sm mt-2">
                                Open
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            @if (auth()->user()->role === 'admin')
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <h5 class="mb-3">Sales vs Purchase Trend</h5>
                        <canvas id="trendChart" height="100"></canvas>
                    </div>
                </div>
            @endif




        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const months = @json($months);

        const sales = months.map(month => {
            return @json($chartData)[month] ?? 0;
        });

        const purchase = months.map(month => {
            return @json($purchaseData)[month] ?? 0;
        });

        const ctx = document.getElementById('trendChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: months.map(m => {
                    return new Date(m + "-01").toLocaleString('default', {
                        month: 'short'
                    });
                }),
                datasets: [{
                        label: 'Sales',
                        data: sales,
                        borderColor: 'blue',
                        backgroundColor: 'rgba(0,123,255,0.2)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Purchase',
                        data: purchase,
                        borderColor: 'red',
                        backgroundColor: 'rgba(255,0,0,0.2)',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }

        });
    </script>
@endpush
