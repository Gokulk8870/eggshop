<header class="main-header">
    <div class="header-left">
        <button class="menu-toggle" id="menuToggle" aria-label="Toggle sidebar">
            <span></span><span></span><span></span>
        </button>
        <span class="app-name">EGG SHOP ERP</span>
    </div>
    <div class="d-flex justify-content-end align-items-center gap-3">

        <div class="d-flex justify-content-end align-items-center">

            <span class="header">
                {{ now()->format('d-m-Y') }}&nbsp;|&nbsp;
                {{ auth()->user()->role }} - {{ auth()->user()->name }}
            </span>

        </div>

    </div>
</header>
