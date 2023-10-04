const Vehicle = () => import('../pages/vehicles/list.vue');
const VehicleCreateEdit = () => import('../pages/vehicles/createEdit.vue');

export default [
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
    }
]





