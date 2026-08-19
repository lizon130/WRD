<div id="layoutSidenav_nav">

    <div class="user_profile">
        <img class="profile-image"
            src="{{ Auth::user()->profile_image ? asset('uploads/user-images/' . Auth::user()->profile_image) : asset('assets/img/no-img.jpg') }}"
            alt="">

        <div class="profile-title">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
        <div class="profile-description">{{ Auth::user()->roles->name }}</div>
    </div>

    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">

            <div class="nav">

                {{-- <a class="nav-link" target="_blank" href="{{ route('home') }}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-globe"></i></div>
                View Website
                </a> --}}

                @if (Helper::hasRight('Dashboard.view'))
                <a class="nav-link {{ Route::is('admin.machineTrans.dashboard') ? 'active' : '' }}"
                    href="{{ route('admin.machineTrans.dashboard') }}"
                    href="{{ route('admin.machineTrans.dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div> Dashboard
                </a>
                @endif

                @if (Helper::hasRight('washDashboard.view'))
                <a class="nav-link {{ Route::is('admin.washReportDashboard') ? 'active' : '' }}"
                    href="{{ route('admin.washReportDashboard') }}" href="{{ route('admin.washReportDashboard') }}">
                    <div class="fas fa-chart-bar"><i class="fas fa-tachometer-alt"></i></div> Wash Report Dashboard
                </a>
                @endif

                @if (Helper::hasRight('setting.view'))
                <a class="nav-link {{ Route::is('dashboard.summary') ? 'active' : '' }}"
                    href="{{ route('dashboard.summary') }}" href="{{ route('dashboard.summary') }}">
                    <div class="fas fa-chart-bar"><i class="fas fa-file-lines"></i></div> Dashboard Summary
                </a>
                @endif

                {{-- Setting --}}
                {{-- @if (Helper::hasRight('setting.view'))
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#settingNav"
                        aria-expanded="@if (Route::is('admin.setting.general') || Route::is('admin.setting.static.content') || Route::is('admin.setting.legal.content') || Route::is('admin.contact') || Route::is('admin.setting.journey.unity.content') || Route::is('admin.resource')) true @else false @endif"
                        aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-gear"></i></div> Setup
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse @if (Route::is('admin.setting.general') || Route::is('admin.setting.static.content') || Route::is('admin.setting.legal.content') || Route::is('admin.contact') || Route::is('admin.setting.journey.unity.content') || Route::is('admin.resource')) show @endif" id="settingNav"
                        aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav down">
                            @if (Helper::hasRight('setting.general'))
                                <a class="nav-link {{ Route::is('admin.setting.general') ? 'active' : '' }}"
                href="{{ route('admin.setting.general') }}"><i
                    class="fa-solid fa-angles-right ikon"></i> General Setting </a>
                @endif

                @if (Helper::hasRight('setting.static-content'))
                <a class="nav-link {{ Route::is('admin.setting.static.content') ? 'active' : '' }}"
                    href="{{ route('admin.setting.static.content') }}"><i
                        class="fa-solid fa-angles-right ikon"></i> Static Content</a>
                @endif
                @if (Helper::hasRight('setting.journey-content'))
                <a class="nav-link {{ Route::is('admin.setting.journey.unity.content') ? 'active' : '' }}"
                    href="{{ route('admin.setting.journey.unity.content') }}"><i
                        class="fa-solid fa-angles-right ikon"></i> Frontend Content</a>
                @endif
                @if (Helper::hasRight('setting.alumni-content'))
                <a class="nav-link {{ Route::is('admin.setting.alumni-content') ? 'active' : '' }}"
                    href="{{ route('admin.setting.alumni-content') }}"><i
                        class="fa-solid fa-angles-right ikon"></i> Alumni Page Content</a>
                @endif
                @if (Helper::hasRight('setting.legal-content'))
                <!--<a class="nav-link {{ Route::is('admin.setting.legal.content') ? 'active' : '' }}"
                                    href="{{ route('admin.setting.legal.content') }}"><i
                                        class="fa-solid fa-angles-right ikon"></i> Legal Content</a>-->
                @endif

                @if (Helper::hasRight('contact.view'))
                <a class="nav-link {{ Route::is('admin.contact') ? 'active' : '' }}"
                    href="{{ route('admin.contact') }}"><i class="fa-solid fa-angles-right ikon"></i>
                    Contact Management
                </a>
                @endif


    </nav>
</div>
@endif --}}


{{-- Addministrator  --}}
@if (Helper::hasRight('setting.view'))
<a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#setupNav"
    aria-expanded="@if (Route::is('admin.role') ||
                                Route::is('admin.role.create') ||
                                Route::is('admin.role.edit') ||
                                Route::is('admin.role.right') ||
                                Route::is('admin.partner') ||
                                Route::is('admin.partner.product') ||
                                Route::is('admin.user')) true @else false @endif"
    aria-controls="collapseLayouts">
    <div class="sb-nav-link-icon"><i class="fa-solid fa-user-tie"></i></div> Administration
    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
</a>
<div class="collapse @if (Route::is('admin.role') ||
                            Route::is('admin.role.create') ||
                            Route::is('admin.role.edit') ||
                            Route::is('admin.role.right') ||
                            Route::is('admin.partner') ||
                            Route::is('admin.partner.product') ||
                            Route::is('admin.user')) show @endif" id="setupNav"
    aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
    <nav class="sb-sidenav-menu-nested nav down">
        @if (Helper::hasRight('role.view'))
        <a class="nav-link {{ Route::is('admin.role') || Route::is('admin.role.create') || Route::is('admin.role.edit') ? 'active' : '' }}"
            href="{{ route('admin.role') }}"><i class="fa-solid fa-angles-right ikon"></i> Role
            Management</a>
        @endif
        <a class="nav-link {{ Route::is('admin.role.right') ? 'active' : '' }}"
            href="{{ route('admin.role.right') }}"><i class="fa-solid fa-angles-right ikon"></i>
            Right Management</a>



        @if (Helper::hasRight('user.view'))
        <a class="nav-link {{ Route::is('admin.user') ? 'active' : '' }}"
            href="{{ route('admin.user') }}"><i class="fa-solid fa-angles-right ikon"></i> User
            Management
        </a>
        @endif
    </nav>
</div>
@endif


{{-- Machine Transfer  --}}
@if (Helper::hasRight('Machine Transfer.all'))

<a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#unitNav"
    aria-expanded="{{ Route::is('admin.unit.user') || Route::is('admin.machineTrans.*') || Route::is('machine-transfer.*') ? 'true' : 'false' }}"
    aria-controls="unitNav">
    <div class="sb-nav-link-icon"><i class="fa-solid fa-shapes"></i></div>
    Unit Management
    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
</a>

<div class="collapse {{ Route::is('admin.unit.user') || Route::is('admin.machineTrans.*') || Route::is('machine-transfer.*') ? 'show' : '' }}"
    id="unitNav" data-bs-parent="#sidenavAccordion">

    <nav class="sb-sidenav-menu-nested nav down">

        @if (Helper::hasRight('unit.view'))
        {{-- Unit List --}}
        @if (Helper::hasRight('unit.view'))
        <a class="nav-link {{ Route::is('admin.unit.user') ? 'active' : '' }}"
            href="{{ route('admin.unit.user') }}">
            <i class="fa-solid fa-angles-right ikon"></i> Unit List
        </a>
        @endif

        {{-- Machine Transfer --}}
        <a class="nav-link {{ Route::is('admin.machineTrans.user') ? 'active' : '' }}"
            href="{{ route('admin.machineTrans.user') }}">
            <i class="fa-solid fa-angles-right ikon"></i> Machine Transfer
        </a>
        @endif

        @if (Helper::hasRight('Transfer Verification.view'))
        {{-- Machine Transfer Approvals --}}
        <a class="nav-link {{ Route::is('machine-transfer.approvals') ? 'active' : '' }}"
            href="{{ route('machine-transfer.approvals') }}">
            <i class="fa-solid fa-angles-right ikon"></i> Transfer Verification
        </a>
        @endif
    </nav>
</div>
@endif

{{-- Dryer Management  --}}
@if (Helper::hasRight('dryer.view'))

<a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#dryerNav"
    aria-expanded="{{ Route::is('admin.dryer.*') ? 'true' : 'false' }}" aria-controls="dryerNav">

    <div class="sb-nav-link-icon">
        <i class="fa-solid fa-fire"></i>
    </div>
    Dryer Management
    <div class="sb-sidenav-collapse-arrow">
        <i class="fas fa-angle-down"></i>
    </div>
</a>

<div class="collapse {{ Route::is('admin.dryer.*') ? 'show' : '' }}" id="dryerNav"
    data-bs-parent="#sidenavAccordion">

    <nav class="sb-sidenav-menu-nested nav down">

        {{-- Dryer List --}}
        @if (Helper::hasRight('dryer.view'))
        <a class="nav-link {{ Route::is('admin.dryer.user') ? 'active' : '' }}"
            href="{{ route('admin.dryer.user') }}">
            <i class="fa-solid fa-angles-right ikon"></i>
            Dryer List
        </a>
        @endif

        <!-- @if (Helper::hasRight('dryer.view'))
                                <a class="nav-link {{ Route::is('admin.dryer-process-manual.*') ? 'active' : '' }}"
                                    href="{{ route('admin.dryer-process-manual.index') }}">
                                    <i class="fa-solid fa-angles-right ikon"></i>
                                    Dryer Process Manual
                                </a>
                            @endif -->
    </nav>
</div>
@endif

{{-- ManPower Management --}}
@if (Helper::hasRight('manpower.view'))

<a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#manpowerNav"
    aria-expanded="{{ Route::is('admin.manpower.*') ? 'true' : 'false' }}"
    aria-controls="manpowerNav">

    <div class="sb-nav-link-icon">
        <i class="fa-solid fa-users"></i>
    </div>
    ManPower Management
    <div class="sb-sidenav-collapse-arrow">
        <i class="fas fa-angle-down"></i>
    </div>
</a>

<div class="collapse {{ Route::is('admin.manpower.*') ? 'show' : '' }}" id="manpowerNav"
    data-bs-parent="#sidenavAccordion">

    <nav class="sb-sidenav-menu-nested nav down">

        {{-- ManPower List --}}
        @if (Helper::hasRight('manpower.view'))
        <a class="nav-link {{ Route::is('admin.manpower.user') ? 'active' : '' }}"
            href="{{ route('admin.manpower.user') }}">
            <i class="fa-solid fa-angles-right ikon"></i>
            Wet Process
        </a>

        <a class="nav-link {{ Route::is('admin.dryprocessie.user') ? 'active' : '' }}"
            href="{{ route('admin.dryprocessie.user') }}">
            <i class="fa-solid fa-angles-right ikon"></i>
            Dry Process
        </a>
        @endif

        {{-- Create ManPower --}}
        @if (Helper::hasRight('manpower.create'))
        <a class="nav-link {{ Route::is('admin.manpower.create.form') ? 'active' : '' }}"
            href="{{ route('admin.manpower.create.form') }}">
            <i class="fa-solid fa-angles-right ikon"></i>
            Add ManPower
        </a>
        @endif

    </nav>
</div>
@endif

{{-- Wash Report Entry Management --}}
@if (Helper::hasRight('wash-report-entry.view'))

<a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
    data-bs-target="#washReportEntryNav"
    aria-expanded="{{ Route::is('admin.wash-report-entry.*') ? 'true' : 'false' }}"
    aria-controls="washReportEntryNav">

    <div class="sb-nav-link-icon">
        <i class="fa-solid fa-water"></i>
    </div>
    Wash Report Entry
    <div class="sb-sidenav-collapse-arrow">
        <i class="fas fa-angle-down"></i>
    </div>
</a>

<div class="collapse {{ Route::is('admin.wash-report-entry.*') ? 'show' : '' }}"
    id="washReportEntryNav" data-bs-parent="#sidenavAccordion">

    <nav class="sb-sidenav-menu-nested nav down">

        {{-- Wash Report Entry List --}}
        @if (Helper::hasRight('wash-report-entry.view'))
        <a class="nav-link {{ Route::is('admin.wash-report-entry.index') ? 'active' : '' }}"
            href="{{ route('admin.wash-report-entry.index') }}">
            <i class="fa-solid fa-angles-right ikon"></i>
            Wash Report List
        </a>

        <a class="nav-link {{ Route::is('admin.second-dry-process.index') ? 'active' : '' }}"
            href="{{ route('admin.second-dry-process.index') }}">
            <i class="fa-solid fa-angles-right ikon"></i>
            Dry Process Entry
        </a>
        @endif

        {{-- Create Wash Report Entry --}}
        @if (Helper::hasRight('wash-report-entry.view'))
        <a class="nav-link {{ Route::is('admin.dry-process-manual.index') ? 'active' : '' }}"
            href="{{ route('admin.dry-process-manual.index') }}">
            <i class="fa-solid fa-angles-right ikon"></i>
            Dry Process Manual
        </a>
        @endif

    </nav>
</div>
@endif

@if (Helper::hasRight('entertainment.view'))
<a class="nav-link {{ Route::is('admin.games') ? 'active' : '' }}" href="{{ route('admin.games') }}"
    href="{{ route('admin.games') }}">
    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div> Entertainment
</a>
@endif
</div>
</div>
</nav>
</div>