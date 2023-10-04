// import router package
import { createWebHistory, createRouter } from "vue-router";
import store from '@/store';

// Guest component
import Login from '../pages/Login.vue';

// Authenticated component
import Dashboard from '../pages/Dashboard.vue';
import User from '../pages/User.vue';
import Tracker from '../pages/Tracker.vue';


// setting
import CompanySetup from '../pages/settings/company_setup.vue';
import Bank from '../pages/settings/bank.vue';
import Expense from '../pages/settings/expense.vue';
import Area from '../pages/settings/area.vue';

// user
import UserList from '../pages/users/list.vue';
import UserRole from '../pages/users/role.vue';
import UserRolePermission from '../pages/users/rolePermission.vue';
import UserActivity from '../pages/users/activity.vue';

// expense
const ExpenseGeneral = () =>
    import ('../pages/expenses/general.vue');
const ExpenseOil = () =>
    import ('../pages/expenses/oil.vue');
const ExpenseChallan = () =>
    import ('../pages/expenses/challan.vue');

const ExpenseReport = () =>
    import ('../pages/expenses/expenseReport.vue');


// Pump
const Pump = () =>
    import ('../pages/pumps/pumpList.vue');
const PumpBill = () =>
    import ('../pages/pumps/pumpBill.vue');
const PumpReport = () =>
    import ('../pages/pumps/pumpReport.vue');

// Vendor
const Vendor = () =>
    import ('../pages/vendors/vendorList.vue');
const VendorBill = () =>
    import ('../pages/vendors/vendorBill.vue');
const VendorReport = () =>
    import ('../pages/vendors/vendorReport.vue');


// staff
const Staff = () =>
    import ('../pages/staffs/list.vue');
const StaffCreateEdit = () =>
    import ('../pages/staffs/createEdit.vue');
const StaffReferenceList = () =>
    import ('../pages/staffs/references/list.vue');

// vehicle
const Vehicle = () =>
    import ('../pages/vehicles/list.vue');
const VehicleCreateEdit = () =>
    import ('../pages/vehicles/createEdit.vue');

// Deposit Challan
const DepositChallanCreateEdit = () =>
    import ('../pages/challans/deposit_challan/createEdit.vue');
const DepositChallanLists = () =>
    import ('../pages/challans/deposit_challan/challanList.vue');
const DepositChallanReport = () =>
    import ('../pages/challans/deposit_challan/challanReport.vue');

// Commission Challan
const CommissionChallanCreateEdit = () =>
    import ('../pages/challans/commission_challan/createEdit.vue');
const CommissionChallanLists = () =>
    import ('../pages/challans/commission_challan/challanList.vue');
const CommissionChallanReport = () =>
    import ('../pages/challans/commission_challan/challanReport.vue');

// Passenger Challan
// const PassengerChallanCreateEdit = () =>
//     import ('../pages/challans/passenger_challan/createEdit.vue');
const PassengerChallanLists = () =>
    import ('../pages/challans/passenger_challan/challanList.vue');
const PassengerChallanReport = () =>
    import ('../pages/challans/passenger_challan/challanReport.vue');

// client
const Client = () =>
    import ('../pages/clients/client.vue');
const ClientBill = () =>
    import ('../pages/clients/bill.vue');
const ClientPaymentReceived = () =>
    import ('../pages/clients/paymentReceived.vue');
const ClientReport = () =>
    import ('../pages/clients/ClientReport.vue');


// vehicle Supplier
const VehicleSupplierLists = () =>
    import ('../pages/vehicle_supplier/VehicleSupplierList.vue');
const VehicleSupplierBill = () =>
    import ('../pages/vehicle_supplier/bill.vue');
const VehicleSupplierPaymentReceived = () =>
    import ('../pages/vehicle_supplier/paymentReceived.vue');
const VehicleSupplierReport = () =>
    import ('../pages/vehicle_supplier/vehicleSupplierReport.vue');

// Account
const AccountList = () =>
    import ('../pages/accounts/accountList.vue');

const AccountTransaction = () =>
    import ('../pages/accounts/accountTransaction.vue');

const AccountReport = () =>
    import ('../pages/accounts/accountReport.vue');


const AllReports = () =>
    import ('../pages/reports/allReport.vue');



const routes = [{
        path: "/login",
        name: "login",
        component: Login,
        meta: {
            layout: 'AuthLayout',
            middleware: "guest",
            title: `Login`,
        }
    },

    {
        path: "/",
        name: "dashboard",
        component: Dashboard,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Dashboard`,
            menu: 'dashboard',
        },
        children: [{
            path: '/dashboard',
            name: "dashboard",
            component: Dashboard,
            meta: {
                layout: 'AdminLayout',
                middleware: "auth",
                title: `Dashboard`,
                menu: 'dashboard',
            }
        }]
    },
    {
        path: "/tracker",
        name: "tracker",
        component: Tracker,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Tracker`,
            menu: 'tracker',
            subMenu: 'tracker'
        }
    },

    // user routes
    {
        path: "/users/list",
        name: "user-list",
        component: UserList,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `user list`,
            menu: 'user',
            subMenu: 'list'
        }
    },
    {
        path: "/users/role",
        name: "user-role",
        component: UserRole,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `user role`,
            menu: 'user',
            subMenu: 'role'
        }
    },
    {
        path: "/users/role/permission",
        name: "user-role-permission",
        component: UserRolePermission,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `user role permission`,
            menu: 'user',
            subMenu: 'role'
        }
    },
    {
        path: "/user/activities",
        name: "user-activities",
        component: UserActivity,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `User activities`,
            menu: 'user-activity',
            subMenu: 'user-activity'
        }
    },

    // settings routes
    {
        path: "/settings/company-setup",
        name: "company-setup",
        component: CompanySetup,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Company setup`,
            menu: 'setting',
            subMenu: 'company-setup'
        }
    },
    {
        path: "/settings/banks",
        name: "banks",
        component: Bank,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Bank list`,
            menu: 'setting',
            subMenu: 'bank'
        }
    },
    {
        path: "/settings/expenses",
        name: "expenses",
        component: Expense,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Expense list`,
            menu: 'setting',
            subMenu: 'expense'
        }
    },
    {
        path: "/settings/areas",
        name: "areas",
        component: Area,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Area list`,
            menu: 'setting',
            subMenu: 'area'
        }
    },

    // clients routes
    {
        path: "/clients",
        name: "clients",
        component: Client,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Client list`,
            menu: 'client',
            subMenu: 'list'
        }
    },
    {
        path: "/client/bills",
        name: "client-bills",
        component: ClientBill,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Bill list`,
            menu: 'client',
            subMenu: 'bill'
        }
    },
    {
        path: "/client/payment-receiveds",
        name: "client-payment-receiveds",
        component: ClientPaymentReceived,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Payment received list`,
            menu: 'client',
            subMenu: 'paymentReceived'
        }
    },
    {
        path: "/client-reports",
        name: "client-reports",
        component: ClientReport,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `client report`,
            menu: 'client',
            subMenu: 'report'
        }
    },


    // expenses routes
    {
        path: "/expenses/generals",
        name: "expense-general",
        component: ExpenseGeneral,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `General expense list`,
            menu: 'expense',
            subMenu: 'general'
        }
    },
    {
        path: "/expenses/oils",
        name: "expense-oil",
        component: ExpenseOil,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Oil expense list`,
            menu: 'expense',
            subMenu: 'oil'
        }
    },
    {
        path: "/expenses/challans",
        name: "expense-challan",
        component: ExpenseChallan,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `challn expense list`,
            menu: 'expense',
            subMenu: 'challan'
        }
    },
    {
        path: "/expenses/reports",
        name: "expense-reports",
        component: ExpenseReport,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Expense Reports`,
            menu: 'expense',
            subMenu: 'report'
        }
    },

    // staffs routes
    {
        path: "/staffs",
        name: "staffs",
        component: Staff,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Staff list`,
            menu: 'staff',
            subMenu: 'list'
        }
    },
    {
        path: "/staffs/create",
        name: "staff-create",
        component: StaffCreateEdit,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Staff create`,
            menu: 'staff',
            subMenu: 'list'
        }
    },
    {
        path: "/staffs/:id/edit",
        name: "staff-edit",
        component: StaffCreateEdit,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Staff edit`,
            menu: 'staff',
            subMenu: 'list'
        }
    },
    {
        path: "/staffs/reference/:id",
        name: "staff-reference-list",
        component: StaffReferenceList,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Staff References`,
            menu: 'staff',
            subMenu: 'list'
        }
    },

    // vehicle
    {
        path: "/vehicles",
        name: "vehicles",
        component: Vehicle,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `vehicle list`,
            menu: 'vehicle',
            subMenu: 'list'
        }
    },
    {
        path: "/vehicles/create",
        name: "vehicle-create",
        component: VehicleCreateEdit,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Vehicle create`,
            menu: 'vehicle',
            subMenu: 'list'
        }
    },
    {
        path: "/vehicles/:id/edit",
        name: "vehicle-edit",
        component: VehicleCreateEdit,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Vehicle edit`,
            menu: 'vehicle',
            subMenu: 'list'
        }
    },

    // deposit challans
    {
        path: "/deposit-challans",
        name: "deposit-challans",
        component: DepositChallanLists,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Deposit challan list`,
            menu: 'deposit_challan',
            subMenu: 'list'
        }
    },
    {
        path: "/deposit-challans/create",
        name: "deposit-challans-create",
        component: DepositChallanCreateEdit,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Challan create`,
            menu: 'deposit_challan',
            subMenu: 'create'
        }
    },
    {
        path: "/deposit-challan/:id",
        name: "deposit-challan/:id",
        component: DepositChallanCreateEdit,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Challan create`,
            menu: 'deposit_challan',
            subMenu: 'create'
        }
    },
    {
        path: "/deposit-challan-reports",
        name: "deposit-challan-reports",
        component: DepositChallanReport,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Deposit challan Reports`,
            menu: 'deposit_challan',
            subMenu: 'reports'
        }
    },

    // Commission challans
    {
        path: "/commission-challans",
        name: "commission-challans",
        component: CommissionChallanLists,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Commission challan list`,
            menu: 'commission_challan',
            subMenu: 'list'
        }
    },
    {
        path: "/commission-challans/create",
        name: "commission-challans-create",
        component: CommissionChallanCreateEdit,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Challan create`,
            menu: 'commission_challan',
            subMenu: 'create'
        }
    },
    {
        path: "/commission-challan/:id",
        name: "commission-challan/:id",
        component: CommissionChallanCreateEdit,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Challan create`,
            menu: 'commission_challan',
            subMenu: 'create'
        }
    },
    {
        path: "/commission-challan-reports",
        name: "commission-challan-reports",
        component: CommissionChallanReport,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Commission challan Reports`,
            menu: 'commission_challan',
            subMenu: 'reports'
        }
    },

    // passenger challans
    {
        path: "/passenger-challans",
        name: "passenger-challans",
        component: PassengerChallanLists,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Passenger challan list`,
            menu: 'passenger_challan',
            subMenu: 'list'
        }
    },
    // {
    //     path: "/passenger-challans/create",
    //     name: "passenger-challans-create",
    //     component: PassengerChallanCreateEdit,
    //     meta: {
    //         layout: 'AdminLayout',
    //         middleware: "auth",
    //         title: `Passenger challan create`,
    //         menu: 'passenger_challan',
    //         subMenu: 'create'
    //     }
    // },
    // {
    //     path: "/passenger-challan/:id",
    //     name: "passenger-challan/:id",
    //     component: PassengerChallanCreateEdit,
    //     meta: {
    //         layout: 'AdminLayout',
    //         middleware: "auth",
    //         title: `Passenger challan edit`,
    //         menu: 'passenger_challan',
    //         subMenu: 'create'
    //     }
    // },
    {
        path: "/passenger-challan-reports",
        name: "passenger-challan-reports",
        component: PassengerChallanReport,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Passenger challan Reports`,
            menu: 'passenger_challan',
            subMenu: 'reports'
        }
    },

    // accounts
    {
        path: "/accounts",
        name: "accounts",
        component: AccountList,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `List of accounts`,
            menu: 'account',
            subMenu: 'list_of_accounts'
        }
    },
    {
        path: "/account-transactions",
        name: "account-transactions",
        component: AccountTransaction,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Account transactions`,
            menu: 'account',
            subMenu: 'transactions'
        }
    },
    {
        path: "/account-reports",
        name: "account-reports",
        component: AccountReport,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Account Reports`,
            menu: 'account',
            subMenu: 'reports'
        }
    },

    // pumps routes
    {
        path: "/pumps",
        name: "pumps",
        component: Pump,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Pump list`,
            menu: 'pump',
            subMenu: 'list'
        }
    },
    {
        path: "/pump/bills",
        name: "pump-bills",
        component: PumpBill,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Bill list`,
            menu: 'pump',
            subMenu: 'bill'
        }
    },
    {
        path: "/pump/reports",
        name: "pump-reports",
        component: PumpReport,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Pump report`,
            menu: 'pump',
            subMenu: 'report'
        }
    },

    // vendors routes
    {
        path: "/vendors",
        name: "vendors",
        component: Vendor,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `vendor list`,
            menu: 'vendor',
            subMenu: 'list'
        }
    },
    {
        path: "/vendor/bills",
        name: "vendor-bills",
        component: VendorBill,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Vendor list`,
            menu: 'vendor',
            subMenu: 'bill'
        }
    },
    {
        path: "/vendor/reports",
        name: "vendor-reports",
        component: VendorReport,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Vendor report`,
            menu: 'vendor',
            subMenu: 'report'
        }
    },

    // vehicle supplier routes
    {
        path: "/vehicle-suppliers",
        name: "vehicle-suppliers",
        component: VehicleSupplierLists,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Vehicle supplier list`,
            menu: 'vehicle_supplier',
            subMenu: 'list'
        }
    },
    {
        path: "/vehicle-supplier/bills",
        name: "vehicle-supplier-bills",
        component: VehicleSupplierBill,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Vehicle supplier list`,
            menu: 'vehicle_supplier',
            subMenu: 'bill'
        }
    },
    {
        path: "/vehicle-supplier/payment-receiveds",
        name: "vehicle-supplier-payment-receiveds",
        component: VehicleSupplierPaymentReceived,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Payment received list`,
            menu: 'vehicle_supplier',
            subMenu: 'paymentReceived'
        }
    },
    {
        path: "/vehicle-supplier/reports",
        name: "vehicle-supplier-reports",
        component: VehicleSupplierReport,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Vehicle supplier report`,
            menu: 'vehicle_supplier',
            subMenu: 'report'
        }
    },
    {
        path: "/all-reports",
        name: "all-reports",
        component: AllReports,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `All reports`,
            menu: 'all_reports',
            subMenu: 'report'
        }
    },



];

const router = createRouter({
    history: createWebHistory(),
    routes // short for `routes: routes`
});

router.beforeEach((to, from, next) => {
    document.title = to.meta.title

    // when a guest user using the system
    if (to.meta.middleware == "guest") {

        // during guest if he authonticated then
        // redirect to dashboard
        if (store.state.auth.authenticated) {
            next({ name: "dashboard" })
        }

        // other wise next
        next()
    } else {
        // auhonticated user can go anywhere
        if (store.state.auth.authenticated) {
            next()

            //  if not authonticatedt then go to login
        } else {

            next({ name: "login" })
        }
    }
})

// base: process.env.BASE_URL,
export default router;