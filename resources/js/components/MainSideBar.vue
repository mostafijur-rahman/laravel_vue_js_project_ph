<template>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <router-link to="/dashboard" class="brand-link">
            <img :src="'/assets/img/logo.svg'">
            <!-- <span class="brand-text font-weight-light">{{ setting.name }}</span> -->
        </router-link>

        <!-- Sidebar -->
        <div class="sidebar">

            <!-- Sidebar user (optional) -->
            <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ asset('assets/dist/img/user7-128x128.jpg') }}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">Alexander Pierce</a>
                </div>
            </div> -->

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                    <li class="nav-item">
                        <router-link  to="/dashboard" class="nav-link" :class="{ 'active font-weight-bold': ($route.meta.menu == 'dashboard') }">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>{{ $t('dashboard') }}</p>
                        </router-link>
                    </li>

                    <!-- <li class="nav-item">
                        <router-link  to="/tracker" class="nav-link"  :class="{ 'active font-weight-bold': ($route.meta.menu == 'tracker') }">
                            <i class="nav-icon fas fa-map-marker-alt"></i>
                            <p>{{ $t('tracker') }}</p>
                        </router-link>
                    </li> -->

                    <!-- <li class="nav-item">
                        <router-link  to="/challan-create" class="nav-link"  :class="{ 'active font-weight-bold': ($route.meta.menu == 'challan_create') }">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>{{ $t('challan_create') }}</p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link  to="/deposit-challans" class="nav-link"  :class="{ 'active font-weight-bold': ($route.meta.menu == 'deposit_challan') }">
                            <i class="nav-icon fas fa-list"></i>
                            <p>{{ $t('deposit_challan') }}</p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link  to="/comission-challans" class="nav-link"  :class="{ 'active font-weight-bold': ($route.meta.menu == 'comission_challan') }">
                            <i class="nav-icon fas fa-list"></i>
                            <p>{{ $t('comission_challan') }}</p>
                        </router-link>
                    </li> -->

                    <!-- <li class="nav-item has-treeview" :class="{ 'menu-open': ($route.meta.menu == 'passenger_challan') }">
                        <a href="#" class="nav-link" :class="{ 'active font-weight-bold': ($route.meta.menu == 'passenger_challan') }">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>{{ $t('passenger_challan') }} <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link 
                                    to="/passenger-challans" 
                                    class="nav-link"
                                    :class="{ 'active': ($route.meta.menu == 'passenger_challan' && $route.meta.subMenu == 'list') }"
                                    >
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('list') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/passenger-challan-reports" class="nav-link" :class="{ 'active': ($route.meta.menu == 'passenger_challan' && $route.meta.subMenu == 'reports') }">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('reports') }}</p>
                                </router-link>
                            </li>
                        </ul>
                    </li> -->
    
                    <li class="nav-item has-treeview" :class="{ 'menu-open': ($route.meta.menu == 'deposit_challan') }">
                        <a href="#" class="nav-link" :class="{ 'active font-weight-bold': ($route.meta.menu == 'deposit_challan') }">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>{{ $t('own_vehicle_challan') }} <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link 
                                    to="/deposit-challans/create" 
                                    class="nav-link"
                                    :class="{ 'active': ($route.meta.menu == 'deposit_challan' && $route.meta.subMenu == 'create') }"
                                    >
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('add_challan') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link 
                                    to="/deposit-challans" 
                                    class="nav-link"
                                    :class="{ 'active': ($route.meta.menu == 'deposit_challan' && $route.meta.subMenu == 'list') }"
                                    >
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('list') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/deposit-challan-reports" class="nav-link" :class="{ 'active': ($route.meta.menu == 'deposit_challan' && $route.meta.subMenu == 'reports') }">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('reports') }}</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item has-treeview" :class="{ 'menu-open': ($route.meta.menu == 'commission_challan') }">
                        <a href="#" class="nav-link" :class="{ 'active font-weight-bold': ($route.meta.menu == 'commission_challan') }">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>{{ $t('commission_challan') }} <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link 
                                    to="/commission-challans/create" 
                                    class="nav-link"
                                    :class="{ 'active': ($route.meta.menu == 'commission_challan' && $route.meta.subMenu == 'create') }"
                                    >
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('add_challan') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link 
                                    to="/commission-challans" 
                                    class="nav-link"
                                    :class="{ 'active': ($route.meta.menu == 'commission_challan' && $route.meta.subMenu == 'list') }"
                                    >
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('list') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link 
                                    to="/commission-challan-reports" 
                                    class="nav-link" 
                                    :class="{ 'active': ($route.meta.menu == 'commission_challan' && $route.meta.subMenu == 'reports') }">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('reports') }}</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item has-treeview" :class="{ 'menu-open': ($route.meta.menu == 'client') }">
                        <a href="#" class="nav-link" :class="{ 'active font-weight-bold': ($route.meta.menu == 'client') }">
                            <i class="nav-icon fas fa-handshake"></i>
                            <p>{{ $t('clients') }} <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link 
                                    to="/client/bills" 
                                    class="nav-link"
                                    :class="{ 'active': ($route.meta.menu == 'client' && $route.meta.subMenu == 'bill') }"
                                    >
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('bill_list') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link 
                                    to="/client/payment-receiveds" 
                                    class="nav-link"
                                    :class="{ 'active': ($route.meta.menu == 'client' && $route.meta.subMenu == 'paymentReceived') }"
                                    >
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('payment_received_list') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link 
                                    to="/clients" 
                                    class="nav-link"
                                    :class="{ 'active': ($route.meta.menu == 'client' && $route.meta.subMenu == 'list') }"
                                    >
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('client_list') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link 
                                    to="/client-reports" 
                                    class="nav-link" 
                                    :class="{ 'active': ($route.meta.menu == 'client' && $route.meta.subMenu == 'report') }">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('reports') }}</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item has-treeview" :class="{ 'menu-open': ($route.meta.menu == 'vehicle_supplier') }">
                        <a href="#" class="nav-link" :class="{ 'active font-weight-bold': ($route.meta.menu == 'vehicle_supplier') }">
                            <i class="nav-icon fas fa-truck"></i>
                            <p>{{ $t('vehicle_suppliers') }} <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link 
                                    to="/vehicle-supplier/bills" 
                                    class="nav-link" 
                                    :class="{ 'active': ($route.meta.menu == 'vehicle_supplier' && $route.meta.subMenu == 'bill') }">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('bill_list') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link 
                                    to="/vehicle-supplier/payment-receiveds" 
                                    class="nav-link"
                                    :class="{ 'active': ($route.meta.menu == 'vehicle_supplier' && $route.meta.subMenu == 'paymentReceived') }"
                                    >
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('payment_paid_list') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link 
                                    to="/vehicle-suppliers" 
                                    class="nav-link" 
                                    :class="{ 'active': ($route.meta.menu == 'vehicle_supplier' && $route.meta.subMenu == 'list') }">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('supplier_list') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link 
                                    to="/vehicle-supplier/reports" 
                                    class="nav-link" 
                                    :class="{ 'active': ($route.meta.menu == 'vehicle_supplier' && $route.meta.subMenu == 'report') }">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('reports') }}</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item has-treeview" :class="{ 'menu-open': ($route.meta.menu == 'expense') }">
                        <a href="#" class="nav-link" :class="{ 'active font-weight-bold': ($route.meta.menu == 'expense') }">
                            <i class="nav-icon fas fa-money-bill-alt"></i>
                            <p>{{ $t('expenses') }} <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link 
                                    to="/expenses/generals" 
                                    class="nav-link"
                                    :class="{ 'active': ($route.meta.menu == 'expense' && $route.meta.subMenu == 'general') }"
                                    >
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('general_expense') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link 
                                    to="/expenses/oils" 
                                    class="nav-link"
                                    :class="{ 'active': ($route.meta.menu == 'expense' && $route.meta.subMenu == 'oil') }"
                                    >
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('oil_expense') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link 
                                    to="/expenses/challans" 
                                    class="nav-link"
                                    :class="{ 'active': ($route.meta.menu == 'expense' && $route.meta.subMenu == 'challan') }"
                                    >
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('challan_expense') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link 
                                    to="/expenses/reports" 
                                    class="nav-link"
                                    :class="{ 'active': ($route.meta.menu == 'expense' && $route.meta.subMenu == 'report') }"
                                    >
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('reports') }}</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item has-treeview" :class="{ 'menu-open': ($route.meta.menu == 'pump') }">
                        <a href="#" class="nav-link" :class="{ 'active font-weight-bold': ($route.meta.menu == 'pump') }">
                            <i class="nav-icon fas fa-gas-pump"></i>
                            <p>{{ $t('pumps') }} <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link to="/pump/bills" class="nav-link" :class="{ 'active': ($route.meta.menu == 'pump' && $route.meta.subMenu == 'bill') }">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('expense_list') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/pumps" class="nav-link" :class="{ 'active': ($route.meta.menu == 'pump' && $route.meta.subMenu == 'list') }">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('pump_list') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/pump/reports" class="nav-link" :class="{ 'active': ($route.meta.menu == 'pump' && $route.meta.subMenu == 'report') }">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('reports') }}</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item has-treeview" :class="{ 'menu-open': ($route.meta.menu == 'vendor') }">
                        <a href="#" class="nav-link" :class="{ 'active font-weight-bold': ($route.meta.menu == 'vendor') }">
                            <i class="nav-icon fas fa-users"></i>
                            <p>{{ $t('vendors') }} <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link to="/vendor/bills" class="nav-link" :class="{ 'active': ($route.meta.menu == 'vendor' && $route.meta.subMenu == 'bill') }">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('bill_list') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/vendors" class="nav-link" :class="{ 'active': ($route.meta.menu == 'vendor' && $route.meta.subMenu == 'list') }">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('vendor_list') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/vendor/reports" class="nav-link" :class="{ 'active': ($route.meta.menu == 'vendor' && $route.meta.subMenu == 'report') }">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('reports') }}</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>       

                    <li class="nav-item has-treeview" :class="{ 'menu-open': ($route.meta.menu == 'vehicle') }">
                        <a href="#" class="nav-link" :class="{ 'active font-weight-bold': ($route.meta.menu == 'vehicle') }">
                            <i class="nav-icon fas fa-truck"></i>
                            <p>{{ $t('own') }} {{ $t('vehicles') }} <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link 
                                    to="/vehicles" 
                                    class="nav-link"
                                    :class="{ 'active': ($route.meta.menu == 'vehicle' && $route.meta.subMenu == 'list') }"
                                    >
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('list') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('report') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item has-treeview" :class="{ 'menu-open': ($route.meta.menu == 'account') }">
                        <a href="#" class="nav-link" :class="{ 'active font-weight-bold': ($route.meta.menu == 'account') }">
                            <i class="nav-icon fas fa-money-check"></i>
                            <p>{{ $t('accounts') }} <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link to="/account-transactions" class="nav-link" :class="{ 'active': ($route.meta.menu == 'account' && $route.meta.subMenu == 'transactions') }">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('transactions') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item" v-if="$store.state.auth.user.company.payment_feature == 'enable'">
                                <router-link to="/accounts" class="nav-link" :class="{ 'active': ($route.meta.menu == 'account' && $route.meta.subMenu == 'list_of_accounts') }">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('list_of_accounts') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item" v-if="$store.state.auth.user.company.payment_feature == 'enable'">
                                <router-link to="/account-reports" class="nav-link" :class="{ 'active': ($route.meta.menu == 'account' && $route.meta.subMenu == 'reports') }">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('reports') }}</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item has-treeview" :class="{ 'menu-open': ($route.meta.menu == 'staff') }">
                        <a href="#" class="nav-link" :class="{ 'active font-weight-bold': ($route.meta.menu == 'staff') }">
                            <i class="nav-icon fas fa-users"></i>
                            <p>{{ $t('staff') }} <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link 
                                    to="/staffs" 
                                    class="nav-link"
                                    :class="{ 'active': ($route.meta.menu == 'staff' && $route.meta.subMenu == 'list') }"
                                    >
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('list') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('report') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <router-link  to="/all-reports" class="nav-link"  :class="{ 'active font-weight-bold': ($route.meta.menu == 'all_reports') }">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>{{ $t('all_reports') }}</p>
                        </router-link>
                    </li>

                    <li class="nav-item has-treeview" :class="{ 'menu-open': ($route.meta.menu == 'user') }">
                        <a href="#" class="nav-link" :class="{ 'active font-weight-bold': ($route.meta.menu == 'user') }">
                            <i class="nav-icon fas fa-user-lock"></i>
                            <p>{{ $t('system_user') }} <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link to="/users/list" class="nav-link" :class="{ 'active': ($route.meta.menu == 'user' && $route.meta.subMenu == 'list') }">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('list') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/users/role" class="nav-link" :class="{ 'active': ($route.meta.menu == 'user' && $route.meta.subMenu == 'role') }">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('role') }}</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item has-treeview">
                        <router-link to="/user/activities" class="nav-link" :class="{ 'active font-weight-bold': ($route.meta.menu == 'user-activity') }">
                            <i class="nav-icon fas fa-users"></i>
                            <p>{{ $t('user_activity') }}</p>
                        </router-link>
                    </li>

                    <li class="nav-item has-treeview" :class="{ 'menu-open': ($route.meta.menu == 'setting') }">
                        <a href="#" class="nav-link" :class="{ 'active font-weight-bold': ($route.meta.menu == 'setting') }">
                            <i class="nav-icon fas fa-wrench"></i>
                            <p>{{ $t('setting') }} <i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <router-link to="/settings/areas" class="nav-link" :class="{ 'active': ($route.meta.menu == 'setting' && $route.meta.subMenu == 'area') }">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('load_unload_point') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/settings/expenses" class="nav-link" :class="{ 'active': ($route.meta.menu == 'setting' && $route.meta.subMenu == 'expense') }">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('expense_head') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/settings/banks" class="nav-link" :class="{ 'active': ($route.meta.menu == 'setting' && $route.meta.subMenu == 'bank') }">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('bank_name') }}</p>
                                </router-link>
                            </li>
                            <li class="nav-item">
                                <router-link to="/settings/company-setup" class="nav-link" :class="{ 'active': ($route.meta.menu == 'setting' && $route.meta.subMenu == 'company-setup') }">
                                    <i class="fa fa-angle-right nav-icon"></i>
                                    <p>{{ $t('company_setup') }}</p>
                                </router-link>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <router-link 
                            to="#" 
                            @click="cacheClear"
                            class="nav-link">
                            <i class="nav-icon fas fa-memory"></i>
                            <p>{{ $t('cache_clear') }}</p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="#" class="nav-link" @click="logout">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>{{ $t('logout') }}</p>
                        </router-link>
                    </li>

                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
</template>

<script>
import { mapActions } from 'vuex';
import { useToast } from "vue-toastification";

export default {

    data(){
        return {
            toast: useToast(),
            user: this.$store.state.auth.user
        }
    },
    created() {},
    methods:{
        ...mapActions({
            signOut:"auth/logout",
            setAuthUserData:"auth/setAuthUserData"
        }),
        async logout(){
            await axios.post('/logout').then(({data})=>{
                this.signOut();
                // this.$router.push({name:"login"});
                this.$router.go(0);
            })
        },
        cacheClear(){
            this.setAuthUserData();
            axios
                .get('/api/common/cacheOptimize')
				.then((response) => {
                    this.$router.go(0);
				}).catch((error) => {
					this.toast.error(error);
				});
        },
    }

}
</script>

