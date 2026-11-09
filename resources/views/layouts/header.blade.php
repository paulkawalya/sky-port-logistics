@guest

@else

@php
    // Get the authenticated user's name
    $name = Auth::user()->name;

    // Extract initials by splitting the name and taking the first character of each word
    $nameParts = explode(' ', $name);
    $initials = strtoupper(substr($nameParts[0], 0, 1)) . (isset($nameParts[1]) ? strtoupper(substr($nameParts[1], 0, 1)) : '');

    // Define the background color
    $backgroundColor = '#17a2b8'; // You can customize this color as needed
@endphp

<header>
    <div class="row w-100 m-0">
        <div class="col px-0">
            <div class="row w-100 align-items-center justify-content-between position-relative">
                <div class="col w-auto hp-flex-none hp-mobile-sidebar-button me-44 px-0" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu">
                    <button type="button" class="btn btn-text btn-icon-only">
                        <i class="ri-menu-fill hp-text-color-black-80 hp-text-color-dark-30 lh-1" style="font-size: 24px;"></i>
                    </button>
                </div>

                <div class="hp-header-text-info col col-lg-14 col-xl-16 hp-header-start-text d-flex align-items-center hp-horizontal-none">
                    <!-- <div class="d-flex overflow-hidden rounded-4 hp-bg-color-black-0 hp-bg-color-dark-100" style="min-width: 45px; width: 45px; height: 45px;">
                            <img src="/app-assets/img/memoji/newspaper.svg" alt="Newspaper" height="80%" style="margin-top: auto; margin-left: auto;">
                        </div> -->

                    <!-- <p class="hp-header-start-text-item hp-input-label fw-normal hp-text-color-black-100 hp-text-color-dark-0 ms-12 mb-0 lh-1 d-flex align-items-center">
                            Announcement?&nbsp;&nbsp;
                            <span class="text-danger">There is a meeting on Tuesday.</span>
                        </p> -->
                </div>

                <div class="hp-horizontal-logo-menu d-flex align-items-center w-auto">
                    <div class="col hp-flex-none w-auto hp-horizontal-block">
                        <div class="hp-header-logo d-flex align-items-center">
                            <!-- <a href="index.html" class="position-relative">
                                    <div class="hp-header-logo-icon position-absolute bg-black-20 hp-bg-dark-90 rounded-circle border border-black-0 hp-border-color-dark-90 d-flex align-items-center justify-content-center" style="width: 18px; height: 18px; top: -5px;">
                                        <svg class="hp-fill-color-dark-0" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0.709473 0L1.67247 10.8L5.99397 12L10.3267 10.7985L11.2912 0H0.710223H0.709473ZM9.19497 3.5325H4.12647L4.24722 4.88925H9.07497L8.71122 8.95575L5.99397 9.70875L3.28047 8.95575L3.09522 6.87525H4.42497L4.51947 7.93275L5.99472 8.33025L5.99772 8.3295L7.47372 7.93125L7.62672 6.21375H3.03597L2.67897 2.208H9.31422L9.19572 3.5325H9.19497Z" fill="#2D3436" />
                                        </svg>
                                    </div>

                                    <img class="hp-logo hp-sidebar-visible hp-dark-none" src="/app-assets/img/logo/logo-small.svg" alt="logo">
                                    <img class="hp-logo hp-sidebar-visible hp-dark-block" src="/app-assets/img/logo/logo-small-dark.svg" alt="logo">
                                    <img class="hp-logo hp-sidebar-hidden hp-dir-none hp-dark-none" src="/app-assets/img/logo/logo.svg" alt="logo">
                                    <img class="hp-logo hp-sidebar-hidden hp-dir-none hp-dark-block" src="/app-assets/img/logo/logo-dark.svg" alt="logo">
                                    <img class="hp-logo hp-sidebar-hidden hp-dir-block hp-dark-none" src="/app-assets/img/logo/logo-rtl.svg" alt="logo">
                                    <img class="hp-logo hp-sidebar-hidden hp-dir-block hp-dark-block" src="/app-assets/img/logo/logo-rtl-dark.svg" alt="logo">
                                </a> -->


                        </div>
                    </div>

                    <div class="col hp-flex-none w-auto hp-horizontal-block hp-horizontal-menu ps-24">

                        <ul class="d-flex flex-wrap align-items-center">

                            @if(Auth::user()->canDo(['view_dasbord']))
                            <li>
                                <!-- <div class="menu-title">DASHBOARDS</div> -->

                                <ul>
                                    <li>
                                        <a href="{{url('home')}}">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Analytics" aria-label="Analytics"></div>

                                            <span class="text-uppercase fw-bolder">
                                                <span class="submenu-item-icon">
                                                    <i class="ri-bar-chart-line"></i>
                                                </span>
                                                <span>Data Insights</span>

                                            </span>
                                        </a>
                                    </li>


                                </ul>
                            </li>
                            @endif

                            <li>
                                @if(Auth::user()->canDo(['view_containers']))
                                {{-- <div class="menu-title">CONTAINERS and Shipments</div> --}}
                                @endif
                                <ul>

                                    <li>
                                        <a href="javascript:;" class="submenu-item">
                                            <span class="text-uppercase fw-bolder">
                                                <span class="submenu-item-icon">
                                                    <i class="ri-ship-fill"></i>
                                                </span>

                                                <span>Containers & Shipments </span>
                                            </span>

                                            <div class="menu-arrow"></div>
                                        </a>

                                        <ul class="submenu-children" data-level="1">

                                            @if(Auth::user()->canDo(['view_containers']))

                                            <li>
                                                <a href="{{route('containers.index')}}" class="text-dark">
                                                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>
                                                    <span> <i class="ri-ship-fill"></i> Containers</span>

                                                </a>
                                            </li>
                                            @endif

                                            @if(Auth::user()->canDo(['view_shipment']))

                                            <li>
                                                <a href="{{route('shippments.index')}}" class="text-dark">
                                                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                                    <span><i class="ri-ship-fill"></i> Shippments</span>
                                                    </span>
                                                </a>
                                            </li>
                                            @endif

                                            @if(Auth::user()->canDo(['view_customers']))

                                            <li>
                                                <a href="{{route('customers.index')}}" class="text-dark">
                                                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                                    <span><i class="ri-group-line"></i> Customers</span>
                                                </a>
                                            </li>
                                            @endif

                                            @if(Auth::user()->canDo(['view_agent']))
                                            <li>
                                                <a href="{{route('agents.index')}}" class="text-dark">
                                                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>
                                                    <span><i class="ri-group-fill"></i> Agents</span>

                                                </a>
                                            </li>

                                            @endif



                                        </ul>
                                    </li>

                                </ul>

                                <ul>


                                </ul>
                            </li>

                            @if(Auth::user()->canDo(['view_quotations']))

                            <li>
                                {{-- <div class="menu-title">QUOTATIONS</div> --}}

                                <ul>

                                    <li>
                                        <a href="javascript:;" class="submenu-item">
                                            <span class="text-uppercase fw-bolder">
                                                <span class="submenu-item-icon">
                                                    <i class="ri-stack-fill"></i>
                                                </span>

                                                <span>Quotation System</span>
                                            </span>

                                            <div class="menu-arrow"></div>
                                        </a>

                                        <ul class="submenu-children" data-level="1">
                                            <li>
                                                <a href="{{route('quotations.index')}}" class="text-dark">
                                                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                                    <span>
                                                        <span>All quatations</span>
                                                    </span>
                                                </a>
                                            </li>

                                        </ul>
                                    </li>
                                </ul>
                            </li>

                            @endif

                            @if(Auth::user()->canDo(['view_shipment_invoice']))
                            <li>
                                {{-- <div class="menu-title">INVOICES AND PAYMENTS</div> --}}

                                <ul>

                                    <li>
                                        <a href="javascript:;" class="submenu-item">
                                            {{-- <span class="text-uppercase fw-bolder">
                                            <span class="submenu-item-icon">
                                                <i class="ri-archive-stack-fill"></i>
                                            </span>
                                            <span>Invoice System</span>
                                        </span> --}}
                                            <span class="text-uppercase fw-bolder">
                                                <span class="submenu-item-icon">
                                                    <i class="ri-stack-line"></i>
                                                </span>

                                                <span>Invoice System</span>
                                            </span>

                                            <div class="menu-arrow"></div>
                                        </a>

                                        <ul class="submenu-children" data-level="1">
                                            <li>
                                                <a href="{{route('invoices.index')}}" class="text-dark">
                                                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                                    <span>
                                                        <span>All invoices</span>
                                                    </span>
                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{url('paid_invoices')}}" class="text-dark">
                                                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                                    <span>Paid invoices</span>

                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{url('pending_invoices')}}" class="text-dark">
                                                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                                    <span>Pending invoices</span>

                                                </a>
                                            </li>

                                            <li>
                                                <a href="{{route('payments.index')}}" class="text-dark">
                                                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>
                                                    <span>Payments</span>

                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{route('quotations.index')}}" class="text-dark">
                                                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                                    <span>
                                                        <span>Manual Requisitions</span>
                                                    </span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

                                </ul>
                            </li>
                            @endif

                            @if(Auth::user()->canDo(['view_requisition','can_view_expenses']))
                            <li>
                                {{-- <div class="menu-title">Requisitions and Expenses</div> --}}

                                <ul>

                                    <li>
                                        <a href="javascript:;" class="submenu-item">

                                            <span class="text-uppercase fw-bolder">
                                                <span class="submenu-item-icon">
                                                    <i class="ri-arrow-up-line"></i>
                                                </span>

                                                <span>Track Requisitions</span>
                                            </span>


                                            <div class="menu-arrow"></div>
                                        </a>

                                        <ul class="submenu-children" data-level="1">
                                            @if(Auth::user()->canDo(['view_requisition']))
                                            <li>
                                                <a href="{{route('requisitions.index')}}" class="text-dark">
                                                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                                    <span>Requisitions</span>

                                                </a>
                                            </li>
                                            @endif

                                            @if(Auth::user()->canDo(['can_view_expenses']))
                                            <li>
                                                <a href="{{route('expenses.index')}}" class="text-dark">
                                                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                                    <span>Expenses</span>

                                                </a>
                                            </li>
                                            @endif


                                        </ul>
                                    </li>

                                </ul>

                            </li>
                            @endif



                            @if(Auth::user()->canDo(['view_users']) || Auth::user()->user_type == 'Admin')
                            <li>
                                {{-- <div class="menu-title">Users & Settings</div> --}}
                                <ul>
                                    <li>
                                        <a href="{{route('users.index')}}" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>
                                            <span class="text-uppercase fw-bolder">
                                                <span class="submenu-item-icon">
                                                    <i class="ri-group-fill lg" width="18"></i>
                                                </span>
                                                <span>Users</span>
                                                <span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif




                        </ul>
                    </div>
                </div>

                <div class="col hp-flex-none w-auto pe-0">
                    <div class="row align-items-center justify-content-end">


                        <!-- User Profile and Logout Buttons -->
                        <div class="d-flex align-items-center">
                            <!-- Logout Button -->
                            <div class="me-3">
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                   class="btn btn-outline-danger btn-sm d-flex align-items-center">
                                    <i class="ri-logout-box-r-line me-1"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>

                            <!-- User Profile -->
                            <div class="hover-dropdown-fade position-relative">
                                <div class="hp-cursor-pointer rounded-4 border hp-border-color-dark-80">
                                    <div class="rounded-3 overflow-hidden m-4 d-flex">
                                        <div class="avatar-item d-flex justify-content-center align-items-center"
                                            style="width: 32px; height: 32px; background-color: {{ $backgroundColor }}; color: white; font-weight: bold; font-size: 16px;">
                                            {{ $initials }}
                                        </div>
                                    </div>
                                </div>

                                <div class="hp-header-profile-menu dropdown-fade position-absolute pt-18" style="top: 100%; width: 260px;">
                                    <div class="rounded hp-bg-black-0 hp-bg-dark-100 px-18 py-24">
                                        <span class="d-block h5 hp-text-color-black-100 hp-text-color-dark-0 mb-16">Profile Settings</span>
                                        <div class="divider my-12"></div>
                                        <div class="row">
                                            <div class="col-12 mt-12">
                                                <a href="{{ route('profile') }}" class="hp-p1-body fw-medium">
                                                    <i class="ri-user-settings-line me-4"></i> Edit Profile
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- End of header -->


<div class="offcanvas offcanvas-start hp-mobile-sidebar bg-black-20 hp-bg-dark-90" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel" style="width: 256px;">
    <div class="offcanvas-header justify-content-between align-items-center ms-16 me-8 mt-16 p-0">
        <div class="w-auto px-0">
            <div class="hp-header-logo d-flex align-items-center">
                <a href="/" class="position-relative">

                    <img class="hp-logo hp-sidebar-visible hp-dark-none" src="/app-assets/img/logo/sifcargo.png" alt="logo">
                    <img class="hp-logo hp-sidebar-visible hp-dark-block" src="/app-assets/img/logo/sifcargo.png" alt="logo">
                    <img class="hp-logo hp-sidebar-hidden hp-dir-none hp-dark-none" src="/app-assets/img/logo/sifcargo.png" alt="logo">
                    <img class="hp-logo hp-sidebar-hidden hp-dir-none hp-dark-block" src="/app-assets/img/logo/sifcargo.png" alt="logo">
                    <img class="hp-logo hp-sidebar-hidden hp-dir-block hp-dark-none" src="/app-assets/img/logo/sifcargo.png" alt="logo">
                    <img class="hp-logo hp-sidebar-hidden hp-dir-block hp-dark-block" src="/app-assets/img/logo/sifcargo.png" alt="logo">
                </a>
            </div>
        </div>

        <div class="w-auto px-0 hp-sidebar-collapse-button hp-sidebar-hidden" data-bs-dismiss="offcanvas" aria-label="Close">
            <button type="button" class="btn btn-text btn-icon-only bg-transparent">
                <i class="ri-close-fill lh-1 hp-text-color-black-80" style="font-size: 24px;"></i>
            </button>
        </div>
    </div>

    <div class="hp-sidebar hp-bg-color-black-20 hp-bg-color-dark-90 border-end border-black-40 hp-border-color-dark-80">
        <div class="hp-sidebar-container">
            <div class="hp-sidebar-header-menu">
                <div class="row justify-content-between align-items-end mx-0">
                    <div class="w-auto px-0 hp-sidebar-collapse-button hp-sidebar-visible">
                        <div class="hp-cursor-pointer">
                            <svg width="8" height="15" viewBox="0 0 8 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.91102 1.73796L0.868979 4.78L0 3.91102L3.91102 0L7.82204 3.91102L6.95306 4.78L3.91102 1.73796Z" fill="#B2BEC3"></path>
                                <path d="M3.91125 12.0433L6.95329 9.00125L7.82227 9.87023L3.91125 13.7812L0.000224113 9.87023L0.869203 9.00125L3.91125 12.0433Z" fill="#B2BEC3"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="w-auto px-0">
                        <div class="hp-header-logo d-flex align-items-center">
                            <a href="/" class="position-relative">

                                <img class="hp-logo hp-sidebar-visible hp-dark-none" src="/app-assets/img/logo/sifcargo.png" alt="logo">
                                <img class="hp-logo hp-sidebar-visible hp-dark-block" src="/app-assets/img/logo/sifcargo.png" alt="logo">
                                <img class="hp-logo hp-sidebar-hidden hp-dir-none hp-dark-none" src="/app-assets/img/logo/sifcargo.png" alt="logo">
                                <img class="hp-logo hp-sidebar-hidden hp-dir-none hp-dark-block" src="/app-assets/img/logo/sifcargo.png" alt="logo">
                                <img class="hp-logo hp-sidebar-hidden hp-dir-block hp-dark-none" src="/app-assets/img/logo/sifcargo.png" alt="logo">
                                <img class="hp-logo hp-sidebar-hidden hp-dir-block hp-dark-block" src="/app-assets/img/logo/sifcargo.png" alt="logo">
                            </a>
                        </div>
                    </div>

                    <div class="w-auto px-0 hp-sidebar-collapse-button hp-sidebar-hidden">
                        <div class="hp-cursor-pointer mb-4">
                            <svg width="8" height="15" viewBox="0 0 8 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.91102 1.73796L0.868979 4.78L0 3.91102L3.91102 0L7.82204 3.91102L6.95306 4.78L3.91102 1.73796Z" fill="#B2BEC3"></path>
                                <path d="M3.91125 12.0433L6.95329 9.00125L7.82227 9.87023L3.91125 13.7812L0.000224113 9.87023L0.869203 9.00125L3.91125 12.0433Z" fill="#B2BEC3"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- offcanvas menu starts here -->
                <ul>

                    @if(Auth::user()->canDo(['view_dasbord']))
                    <li>
                        <!-- <div class="menu-title">DASHBOARDS</div> -->

                        <ul>
                            <li>
                                <a href="{{url('home')}}">
                                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Analytics" aria-label="Analytics"></div>

                                    <span class="text-uppercase fw-bolder">
                                        <span class="submenu-item-icon">
                                            <i class="ri-bar-chart-line"></i>
                                        </span>
                                        <span>Data Insights</span>

                                    </span>
                                </a>
                            </li>


                        </ul>
                    </li>
                    @endif

                    <li>
                        @if(Auth::user()->canDo(['view_containers']))
                        {{-- <div class="menu-title">CONTAINERS and Shipments</div> --}}
                        @endif
                        <ul>

                            <li>
                                <a href="javascript:;" class="submenu-item">
                                    <span class="text-uppercase fw-bolder">
                                        <span class="submenu-item-icon">
                                            <i class="ri-ship-fill"></i>
                                        </span>

                                        <span>Containers & Shipments </span>
                                    </span>

                                    <div class="menu-arrow"></div>
                                </a>

                                <ul class="submenu-children" data-level="1">

                                    @if(Auth::user()->canDo(['view_containers']))

                                    <li>
                                        <a href="{{route('containers.index')}}" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>
                                            <span> <i class="ri-ship-fill"></i> Containers</span>

                                        </a>
                                    </li>
                                    @endif

                                    @if(Auth::user()->canDo(['view_shipment']))

                                    <li>
                                        <a href="{{route('shippments.index')}}" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                            <span><i class="ri-ship-fill"></i> Shippments</span>
                                            </span>
                                        </a>
                                    </li>
                                    @endif

                                    @if(Auth::user()->canDo(['view_customers']))

                                    <li>
                                        <a href="{{route('customers.index')}}" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                            <span><i class="ri-group-line"></i> Customers</span>
                                        </a>
                                    </li>
                                    @endif

                                    @if(Auth::user()->canDo(['view_agent']))
                                    <li>
                                        <a href="{{route('agents.index')}}" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>
                                            <span><i class="ri-group-fill"></i> Agents</span>

                                        </a>
                                    </li>

                                    @endif



                                </ul>
                            </li>

                        </ul>

                        <ul>


                        </ul>
                    </li>

                    @if(Auth::user()->canDo(['view_quotations']))

                    <li>
                        {{-- <div class="menu-title">QUOTATIONS</div> --}}

                        <ul>

                            <li>
                                <a href="javascript:;" class="submenu-item">
                                    <span class="text-uppercase fw-bolder">
                                        <span class="submenu-item-icon">
                                            <i class="ri-stack-fill"></i>
                                        </span>

                                        <span>Quotation System</span>
                                    </span>

                                    <div class="menu-arrow"></div>
                                </a>

                                <ul class="submenu-children" data-level="1">
                                    <li>
                                        <a href="{{route('quotations.index')}}" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                            <span>
                                                <span>All quatations</span>
                                            </span>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                        </ul>
                    </li>

                    @endif

                    @if(Auth::user()->canDo(['view_shipment_invoice']))
                    <li>
                        {{-- <div class="menu-title">INVOICES AND PAYMENTS</div> --}}

                        <ul>

                            <li>
                                <a href="javascript:;" class="submenu-item">
                                    {{-- <span class="text-uppercase fw-bolder">
                                            <span class="submenu-item-icon">
                                                <i class="ri-archive-stack-fill"></i>
                                            </span>
                                            <span>Invoice System</span>
                                        </span> --}}
                                    <span class="text-uppercase fw-bolder">
                                        <span class="submenu-item-icon">
                                            <i class="ri-stack-line"></i>
                                        </span>

                                        <span>Invoice System</span>
                                    </span>

                                    <div class="menu-arrow"></div>
                                </a>

                                <ul class="submenu-children" data-level="1">
                                    <li>
                                        <a href="{{route('invoices.index')}}" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                            <span>
                                                <span>All invoices</span>
                                            </span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{url('paid_invoices')}}" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                            <span>Paid invoices</span>

                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{url('pending_invoices')}}" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                            <span>Pending invoices</span>

                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{route('payments.index')}}" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>
                                            <span>Payments</span>

                                        </a>
                                    </li>
                                    @if(Auth::user()->canDo(['view_quotations']))
                                        <li>
                                            <a href="{{route('quotations.index')}}" class="text-dark">
                                                <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                                <span>
                                                    <span>Manual Requisitions</span>
                                                </span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>

                        </ul>
                    </li>
                    @endif

                    @if(Auth::user()->canDo(['view_requisition','can_view_expenses']))
                    <li>
                        {{-- <div class="menu-title">Requisitions and Expenses</div> --}}

                        <ul>

                            <li>
                                <a href="javascript:;" class="submenu-item">

                                    <span class="text-uppercase fw-bolder">
                                        <span class="submenu-item-icon">
                                            <i class="ri-arrow-up-line"></i>
                                        </span>

                                        <span>Track Requisitions</span>
                                    </span>


                                    <div class="menu-arrow"></div>
                                </a>

                                <ul class="submenu-children" data-level="1">
                                    @if(Auth::user()->canDo(['view_requisition']))
                                    <li>
                                        <a href="{{route('requisitions.index')}}" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                            <span>Requisitions</span>

                                        </a>
                                    </li>
                                    @endif

                                    @if(Auth::user()->canDo(['can_view_expenses']))
                                    <li>
                                        <a href="{{route('expenses.index')}}" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                            <span>Expenses</span>

                                        </a>
                                    </li>
                                    @endif


                                </ul>
                            </li>

                        </ul>

                    </li>
                    @endif



                    @if(Auth::user()->canDo(['view_users']) || Auth::user()->user_type == 'Admin')
                    <li>
                        {{-- <div class="menu-title">Users & Settings</div> --}}
                        <ul>
                            <li>
                                <a href="{{route('users.index')}}" class="text-dark">
                                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>
                                    <span class="text-uppercase fw-bolder">
                                        <span class="submenu-item-icon">
                                            <i class="ri-group-fill lg" width="18"></i>
                                        </span>
                                        <span>Users</span>
                                        <span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif




                </ul>
            </div>

            <div class="row justify-content-between align-items-center hp-sidebar-footer mx-0 hp-bg-color-dark-90">
                <div class="divider border-black-40 hp-border-color-dark-70 hp-sidebar-hidden mt-0 px-0"></div>

                <div class="col">
                    <div class="row align-items-center">
                        <!-- <div class="w-auto px-0">
                            <div class="avatar-item bg-primary-4 d-flex align-items-center justify-content-center rounded-circle" style="width: 48px; height: 48px;">
                                <img src="/app-assets/img/memoji/user-avatar-8.png" height="100%" class="hp-img-cover">
                            </div>
                        </div> -->

                        <div class="w-auto ms-8 px-0 hp-sidebar-hidden mt-4">
                            <span class="d-block hp-text-color-black-100 hp-text-color-dark-0 hp-p1-body lh-1">{{Auth::user()->name}}</span>
                            <a href="#" class="hp-badge-text fw-normal hp-text-color-dark-30">{{Auth::user()->user_type}}</a> <br>
                            <a href="/profile" class="hp-badge-text fw-normal hp-text-color-dark-30">Change Password</a>
                        </div>
                    </div>
                </div>

                <div class="col hp-flex-none w-auto px-0 hp-sidebar-hidden">
                    <a href="profile-information.html">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" class="remix-icon hp-text-color-black-100 hp-text-color-dark-0" height="24" width="24" xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <path fill="none" d="M0 0h24v24H0z"></path>
                                <path d="M3.34 17a10.018 10.018 0 0 1-.978-2.326 3 3 0 0 0 .002-5.347A9.99 9.99 0 0 1 4.865 4.99a3 3 0 0 0 4.631-2.674 9.99 9.99 0 0 1 5.007.002 3 3 0 0 0 4.632 2.672c.579.59 1.093 1.261 1.525 2.01.433.749.757 1.53.978 2.326a3 3 0 0 0-.002 5.347 9.99 9.99 0 0 1-2.501 4.337 3 3 0 0 0-4.631 2.674 9.99 9.99 0 0 1-5.007-.002 3 3 0 0 0-4.632-2.672A10.018 10.018 0 0 1 3.34 17zm5.66.196a4.993 4.993 0 0 1 2.25 2.77c.499.047 1 .048 1.499.001A4.993 4.993 0 0 1 15 17.197a4.993 4.993 0 0 1 3.525-.565c.29-.408.54-.843.748-1.298A4.993 4.993 0 0 1 18 12c0-1.26.47-2.437 1.273-3.334a8.126 8.126 0 0 0-.75-1.298A4.993 4.993 0 0 1 15 6.804a4.993 4.993 0 0 1-2.25-2.77c-.499-.047-1-.048-1.499-.001A4.993 4.993 0 0 1 9 6.803a4.993 4.993 0 0 1-3.525.565 7.99 7.99 0 0 0-.748 1.298A4.993 4.993 0 0 1 6 12c0 1.26-.47 2.437-1.273 3.334a8.126 8.126 0 0 0 .75 1.298A4.993 4.993 0 0 1 9 17.196zM12 15a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0-2a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"></path>
                            </g>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endguest

