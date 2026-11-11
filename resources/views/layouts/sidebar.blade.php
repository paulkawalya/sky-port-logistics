@guest

@else
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

                        <!-- <a href="#" target="_blank" class="d-flex">
                                <span class="hp-sidebar-hidden hp-caption fw-normal hp-text-color-primary-1">SIFCARGO TONIGHT</span>
                            </a> -->
                    </div>
                </div>

                <div class="w-auto px-0 hp-sidebar-collapse-button hp-sidebar-hidden">
                    <div class="hp-cursor-pointer mb-4">
                        <i class="ri-menu-3-line"></i>
                    </div>
                </div>
            </div>

            <!-- sidebar goes here -->
            <ul>

                @if(Auth::user()->canDo(['view_dasbord']))
                    <li>
                        <!-- <div class="menu-title">DASHBOARDS</div> -->

                        <ul>
                            <li>
                                <a href="{{url('home')}}">
                                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Analytics" aria-label="Analytics"></div>

                                    <span class="text-uppercase fw-bolder" style="font-size: 12px;">
                                        <span class="submenu-item-icon">
                                            <i class="ri-dashboard-line"></i>
                                        </span>
                                        <span>Data Insights</span>

                                    </span>
                                </a>
                            </li>


                        </ul>
                    </li>
                @endif

                @if(Auth::user()->canDo(['view_finance_dashboard']))
                    <li>
                        <ul>
                            <li>
                                <a href="{{route('finance.dashboard')}}">
                                    <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Finance Dashboard" aria-label="Finance Dashboard"></div>

                                    <span class="text-uppercase fw-bolder" style="font-size: 12px;">
                                        <span class="submenu-item-icon">
                                            <i class="ri-line-chart-line"></i>
                                        </span>
                                        <span>Finance Dashboard</span>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if(Auth::user()->canDo(['view_containers']))
                    <li>
                        <ul>

                            <li>
                                <a href="javascript:;" class="submenu-item">
                                    <span class="text-uppercase fw-bolder" style="font-size: 12px;">
                                        <span class="submenu-item-icon">
                                            <i class="ri-ship-2-line"></i>
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
                                            <span> <i class="ri-inbox-archive-line"></i> Containers</span>

                                        </a>
                                    </li>
                                    @endif

                                    @if(Auth::user()->canDo(['view_shipment']))

                                    <li>
                                        <a href="{{route('shippments.index')}}" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                            <span><i class="ri-truck-line"></i> Shippments</span>
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
                    </li>
                @endif

                @if(Auth::user()->canDo(['view_shipment_invoice']))
                    <li>
                        <ul>

                            <li>
                                <a href="javascript:;" class="submenu-item">

                                    <span class="text-uppercase fw-bolder" style="font-size: 12px;">
                                        <span class="submenu-item-icon">
                                            <i class="ri-bill-line"></i>
                                        </span>

                                        <span>Invoice System</span>
                                    </span>

                                    <div class="menu-arrow"></div>
                                </a>

                                <ul class="submenu-children" data-level="1">
                                    <li>
                                        <a href="{{route('invoices.index')}}" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                            <span><i class="ri-file-list-3-line"></i> All invoices</span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="/invoices?filter=paid" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                            <span><i class="ri-checkbox-circle-line"></i> Paid invoices</span>

                                        </a>
                                    </li>

                                    <li>
                                        <a href="/invoices?filter=pending" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                            <span><i class="ri-time-line"></i> Pending invoices</span>

                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{route('payments.index')}}" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>
                                            <span><i class="ri-money-dollar-circle-line"></i> Payments</span>

                                        </a>
                                    </li>
                                    @if(Auth::user()->canDo(['view_quotations']))
                                    <li>
                                        <a href="{{route('quotations.index')}}" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                            <span><i class="ri-draft-line"></i> Manual Invoices & Quotations</span>
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
                         <ul>
                            <li>
                                <a href="javascript:;" class="submenu-item">

                                    <span class="text-uppercase fw-bolder" style="font-size: 12px;">
                                        <span class="submenu-item-icon">
                                            <i class="ri-file-paper-2-line"></i>
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

                                            <span> <i class="ri-file-list-line"></i> Requisitions</span>

                                        </a>
                                    </li>
                                    @endif

                                    @if(Auth::user()->canDo(['can_view_expenses']))

                                    <li>
                                        <a href="{{route('expenses.index')}}" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                            <span> <i class="ri-money-dollar-box-line"></i> Expenses</span>

                                        </a>
                                    </li>

                                    @endif

                                    @if(Auth::user()->canDo(['manage_expense_categories']))

                                    <li>
                                        <a href="{{route('expense_categories.index')}}" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Contact" aria-label="Contact"></div>

                                            <span> <i class="ri-price-tag-3-line"></i> Expense Category</span>

                                        </a>
                                    </li>
                                    @endif


                                </ul>
                            </li>

                        </ul>

                    </li>
                @endif

                @if(Auth::user()->canDo(['view_transaction_records']) || Auth::user()->canDo(['view_payment_accounts']))
                    <li>
                        <ul>
                            <li>
                                <a href="javascript:;" class="submenu-item">
                                    <span class="text-uppercase fw-bolder" style="font-size: 12px;">
                                        <span class="submenu-item-icon">
                                            <i class="ri-bank-line"></i>
                                        </span>
                                        <span>Accounts</span>
                                    </span>
                                    <div class="menu-arrow"></div>
                                </a>

                                <ul class="submenu-children" data-level="1">
                                    @if(Auth::user()->canDo(['view_payment_accounts']))
                                        <li>
                                            <a href="{{route('payment_accounts.index')}}" class="text-dark">
                                                <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Accounts and Transfers" aria-label="Accounts and Transfers"></div>
                                                <span><i class="ri-exchange-funds-line"></i> Accounts and Transfers</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if(Auth::user()->canDo(['view_transaction_records']))
                                        <li>
                                            <a href="{{route('transactions.index')}}" class="text-dark">
                                                <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Transactions" aria-label="Transactions"></div>
                                                <span><i class="ri-exchange-dollar-line"></i> Transactions</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        </ul>
                    </li>
                @endif

                @if(Auth::user()->canDo(['view_taxes']) || Auth::user()->canDo(['view_suppliers']))
                    <li>
                        <ul>
                            <li>
                                <a href="javascript:;" class="submenu-item">
                                    <span class="text-uppercase fw-bolder" style="font-size: 12px;">
                                        <span class="submenu-item-icon">
                                            <i class="ri-settings-3-line"></i>
                                        </span>
                                        <span>FINANCE  Settings</span>
                                    </span>
                                    <div class="menu-arrow"></div>
                                </a>

                                <ul class="submenu-children" data-level="1">
                                    @if(Auth::user()->canDo(['view_taxes']))
                                        <li>
                                            <a href="{{route('taxes.index')}}" class="text-dark">
                                                <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Taxes" aria-label="Taxes"></div>
                                                <span><i class="ri-token-swap-fill"></i> Taxes</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if(Auth::user()->canDo(['view_suppliers']))
                                        <li>
                                            <a href="{{route('suppliers.index')}}" class="text-dark">
                                                <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Vendors/Suppliers" aria-label="Vendors/Suppliers"></div>
                                                <span><i class="ri-group-3-fill"></i> Vendors/Suppliers</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        </ul>
                    </li>
                @endif

                @if(Auth::user()->canDo(['view_reports']))
                    <li>
                        <ul>
                            <li>
                                <a href="javascript:;" class="submenu-item">
                                    <span class="text-uppercase fw-bolder" style="font-size: 12px;">
                                        <span class="submenu-item-icon">
                                            <i class="ri-file-chart-line"></i>
                                        </span>
                                        <span>Reports</span>
                                    </span>
                                    <div class="menu-arrow"></div>
                                </a>

                                <ul class="submenu-children" data-level="1">
                                    <li>
                                        <a href="{{route('reports.financial')}}" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Financial Reports" aria-label="Financial Reports"></div>
                                            <span><i class="ri-money-dollar-circle-line"></i> Financial Reports</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('reports.sales')}}" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Sales Reports" aria-label="Sales Reports"></div>
                                            <span><i class="ri-shopping-cart-line"></i> Sales Reports</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('reports.expenses')}}" class="text-dark">
                                            <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Expense Reports" aria-label="Expense Reports"></div>
                                            <span><i class="ri-funds-line"></i> Expense Reports</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                @endif

                @if(in_array(Auth::user()->user_type, ['Admin', 'MD', 'Manager']))
                    <li>
                        <ul>
                            <li>
                                <a href="{{route('admin.settings')}}" class="submenu-item">
                                    <span class="text-uppercase fw-bolder" style="font-size: 12px;">
                                        <span class="submenu-item-icon">
                                            <i class="ri-admin-line"></i>
                                        </span>
                                        <span>Admin Settings</span>
                                    </span>
                                    <div class="menu-arrow"></div>
                                </a>

                                <ul class="submenu-children" data-level="1">
                                    @if(Auth::user()->canDo(['view_users']) || in_array(Auth::user()->user_type, ['Admin', 'MD', 'Manager']))
                                        <li>
                                            <a href="{{route('users.index')}}" class="text-dark">
                                                <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Users" aria-label="Users"></div>
                                                <span><i class="ri-user-settings-line"></i> Users</span>
                                            </a>
                                        </li>
                                    @endif
                                    @if(in_array(Auth::user()->user_type, ['Admin', 'MD', 'Manager']))
                                        <li>
                                            <a href="{{route('backup.database')}}" class="text-dark">
                                                <div class="tooltip-item in-active" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="Backup Database" aria-label="Backup Database"></div>
                                                <span><i class="ri-database-2-line"></i> Backup Database</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
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
                        <div class="mt-2">
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();"
                               class="btn btn-danger btn-sm d-flex align-items-center mt-2" style="width: fit-content;">
                                <i class="ri-logout-box-r-line me-1"></i> Logout
                            </a>
                            <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
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
@endguest

