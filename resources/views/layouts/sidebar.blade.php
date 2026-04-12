<nav class="sidebar" id="sidebar">
    <div class="sidebar-body">
        <ul class="nav">
            @if (auth()->check() && auth()->user()->role === 'admin')
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('home') ? 'active' : '' }}">
                        <i data-feather="home"></i><span class="link-title">Dashboard</span>
                    </a>
                </li>
            @endif
            @if (auth()->check() && auth()->user()->role === 'employee')
                <li class="nav-item">
                    <a href="{{ route('employee.dashboard') }}" class="{{ request()->is('home') ? 'active' : '' }}">
                        <i data-feather="home"></i><span class="link-title">Dashboard</span>
                    </a>
                </li>
            @endif

            @if (auth()->check() && in_array(auth()->user()->role, ['admin', 'employee']))
                <li class="nav-item">
                    <a href="#" class="toggle-menu {{ request()->is('customers*') ? 'active' : '' }}">
                        <i data-feather="users"></i>
                        <span class="link-title">Customer</span>
                        <span class="arrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                stroke="#555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9" />
                            </svg></span>
                    </a>
                    <ul class="sub-menu {{ request()->is('customers*') ? 'open' : '' }}">
                        <li class="{{ request()->is('customers/create') ? 'active' : '' }}">
                            <a href="{{ route('customers.create') }}">
                                <span class="circle"></span> Create Customer
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('customers.index') ? 'active' : '' }}">
                            <a href="{{ route('customers.index') }}">
                                <span class="circle"></span> Manage Customer
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="toggle-menu {{ request()->is('salesinvoices*') ? 'active' : '' }}">
                        <i data-feather="clipboard"></i>
                        <span class="link-title">Sales Invoice</span>
                        <span class="arrow">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#555"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </span>
                    </a>

                    <ul class="sub-menu {{ request()->is('salesinvoices*') ? 'open' : '' }}">

                        <!-- Create -->
                        <li class="{{ request()->is('salesinvoices/create') ? 'active' : '' }}">
                            <a href="{{ route('salesinvoices.create') }}">
                                <span class="circle"></span> Create Invoice
                            </a>
                        </li>

                        <!-- Manage -->
                        <li class="{{ request()->routeIs('salesinvoices.index') ? 'active' : '' }}">
                            <a href="{{ route('salesinvoices.index') }}">
                                <span class="circle"></span> Manage Invoice
                            </a>
                        </li>

                    </ul>
                </li>
            @endif

            @if (auth()->check() && auth()->user()->role === 'admin')
                <li class="nav-item">
                    <a href="#" class="toggle-menu {{ request()->is('products*') ? 'active' : '' }}">
                        <i data-feather="archive"></i>
                        <span class="link-title">Products</span>

                        <span class="arrow">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#555"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </span>
                    </a>

                    <ul class="sub-menu {{ request()->is('products*') ? 'open' : '' }}">

                        <!-- Create Product -->
                        <li class="{{ request()->is('products/create') ? 'active' : '' }}">
                            <a href="{{ route('products.create') }}">
                                <span class="circle"></span> Create Product
                            </a>
                        </li>

                        <!-- Manage Products -->
                        <li class="{{ request()->routeIs('products.index') ? 'active' : '' }}">
                            <a href="{{ route('products.index') }}">
                                <span class="circle"></span> Manage Products
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="toggle-menu {{ request()->is('suppliers*') ? 'active' : '' }}">
                        <i data-feather="user-check"></i>
                        <span class="link-title">Supplier</span>

                        <span class="arrow">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#555"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </span>
                    </a>

                    <ul class="sub-menu {{ request()->is('suppliers*') ? 'open' : '' }}">

                        <!-- Create Supplier -->
                        <li class="{{ request()->is('suppliers/create') ? 'active' : '' }}">
                            <a href="{{ route('suppliers.create') }}">
                                <span class="circle"></span> Create Supplier
                            </a>
                        </li>

                        <!-- Manage Supplier -->
                        <li class="{{ request()->routeIs('suppliers.index') ? 'active' : '' }}">
                            <a href="{{ route('suppliers.index') }}">
                                <span class="circle"></span> Manage Supplier
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="toggle-menu {{ request()->is('purchaseinvoices*') ? 'active' : '' }}">
                        <i data-feather="file-text"></i>
                        <span class="link-title">Purchase Invoice</span>

                        <span class="arrow">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#555"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </span>
                    </a>

                    <ul class="sub-menu {{ request()->is('purchaseinvoices*') ? 'open' : '' }}">

                        <!-- Create Expense -->
                        <li class="{{ request()->is('purchaseinvoices/create') ? 'active' : '' }}">
                            <a href="{{ route('purchaseinvoices.create') }}">
                                <span class="circle"></span> Create Purchase Invoice
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('purchaseinvoices.index') ? 'active' : '' }}">
                            <a href="{{ route('purchaseinvoices.index') }}">
                                <span class="circle"></span> Manage Purchase Invoice
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="toggle-menu {{ request()->is('trays*') ? 'active' : '' }}">
                        <i data-feather="grid"></i>
                        <span class="link-title">Tray</span>

                        <span class="arrow">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#555"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </span>
                    </a>

                    <ul class="sub-menu {{ request()->is('trays*') ? 'open' : '' }}">

                        <!-- Create Expense -->
                        <li class="{{ request()->is('trays/create') ? 'active' : '' }}">
                            <a href="{{ route('trays.create') }}">
                                <span class="circle"></span> Create Tray
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('trays/index') ? 'active' : '' }}">
                            <a href="{{ route('trays.index') }}">
                                <span class="circle"></span> Manage Tray
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('trays/return') ? 'active' : '' }}">
                            <a href="{{ route('tray.return.store') }}">
                                <span class="circle"></span> Tray Return
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="toggle-menu {{ request()->is('expenses*') ? 'active' : '' }}">
                        <i data-feather="corner-right-up"></i>
                        <span class="link-title">Expenses</span>

                        <span class="arrow">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#555"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </span>
                    </a>

                    <ul class="sub-menu {{ request()->is('expenses*') ? 'open' : '' }}">

                        <!-- Create Expense -->
                        <li class="{{ request()->is('expenses/create') ? 'active' : '' }}">
                            <a href="{{ route('expenses.create') }}">
                                <span class="circle"></span> Create Expense
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('expenses.index') ? 'active' : '' }}">
                            <a href="{{ route('expenses.index') }}">
                                <span class="circle"></span> Manage Expenses
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="toggle-menu {{ request()->is('reports*') ? 'active' : '' }}">
                        <i data-feather="bar-chart-2"></i>
                        <span class="link-title">Reports</span>
                        <span class="arrow">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#555"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </span>
                    </a>
                    <ul class="sub-menu {{ request()->is('reports/*') ? 'open' : '' }}">

                        {{-- <li class="{{ request()->routeIs('report.stock') ? 'active' : '' }}">
                            <a href="{{ route('report.stock') }}">
                                <span class="circle"></span> Stock Report
                            </a>
                        </li> --}}
                        <li class="{{ request()->routeIs('report.product') ? 'active' : '' }}">
                            <a href="{{ route('report.product') }}">
                                <span class="circle"></span> Product Report
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('report.tray') ? 'active' : '' }}">
                            <a href="{{ route('report.tray') }}">
                                <span class="circle"></span> Tray Report
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('report.sales') ? 'active' : '' }}">
                            <a href="{{ route('report.sales') }}">
                                <span class="circle"></span> Sales Invoice Report
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('report.purchase') ? 'active' : '' }}">
                            <a href="{{ route('report.purchase') }}">
                                <span class="circle"></span> Purchase Invoice Report
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('report.profitloss') ? 'active' : '' }}">
                            <a href="{{ route('report.profitloss') }}">
                                <span class="circle"></span> Profit & Loss Report
                            </a>
                        </li>

                    </ul>
                </li>

                </li>
                <li class="nav-item">
                    <a href="#" class="toggle-menu {{ request()->is('users*') ? 'active' : '' }}">
                        <i data-feather="shield"></i>
                        <span class="link-title">User Control</span>
                        <span class="arrow"><svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                stroke="#555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9" />
                            </svg></span>
                    </a>
                    <ul class="sub-menu {{ request()->is('users*') ? 'open' : '' }}">
                        <li class="{{ request()->is('users/create') ? 'active' : '' }}">
                            <a href="{{ route('users.create') }}">
                                <span class="circle"></span> Creat New User
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
                            <a href="{{ route('users.index') }}">
                                <span class="circle"></span> Manage User
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#"
                        class="toggle-menu {{ request()->is('salesclts*') || request()->is('purchaseclts*') ? 'active' : '' }}">
                        <i data-feather="settings"></i>
                        <span class="link-title">Settings</span>

                        <span class="arrow">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#555"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </span>
                    </a>

                    <ul
                        class="sub-menu {{ request()->is('salesclts*') || request()->is('purchaseclts*') ? 'open' : '' }}">

                        <!-- Sales Control -->
                        <li class="{{ request()->is('salesclts*') ? 'active' : '' }}">
                            <a href="{{ route('salesclts.index') }}">
                                <span class="circle"></span> Sales Control
                            </a>
                        </li>

                        <!-- Purchase Control -->
                        <li class="{{ request()->is('purchasecon*') ? 'active' : '' }}">
                            <a href="{{ route('purchasecon.index') }}">
                                <span class="circle"></span> Purchase Control
                            </a>
                        </li>


                    </ul>
                </li>
            @endif

            <li class="nav-item">
                <a href="{{ route('logout') }}">
                    <i data-feather="log-out"></i><span class="link-title">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
