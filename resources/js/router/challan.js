const ChallanCreateEdit = () => import('../pages/challans/createEdit.vue');
const DepositChallanLists = () => import('../pages/challans/deposit_challan_list.vue');

export default [
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
        component: ChallanCreateEdit,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Challan create`,
            menu: 'deposit_challan',
            subMenu: 'create'
        }
    },

    { 
        path: "/comission-challans",
        name: "comission-challans",
        component: DepositChallanLists,
        meta: {
            layout: 'AdminLayout',
            middleware: "auth",
            title: `Comission challan list`,
            menu: 'comission_challan',
            subMenu: 'list'
        }
    },


]