 // load bootstrap
 import './bootstrap';

 // load vue
 import { createApp } from 'vue';

 // load custom config
 import appConfig from "./app-config";

 // load vue toast
 import Toast from "vue-toastification";
 import "vue-toastification/dist/index.css";
 const toastOptions = {
     position: 'bottom-right',
     timeout: 3000
         // You can set your default options here
 };

 // load sweet alert 2
 import VueSweetalert2 from 'vue-sweetalert2';
 import 'sweetalert2/dist/sweetalert2.min.css';


 // load i18n
 import { createI18n } from 'vue-i18n';
 // set Locales
 import en from './locales/en.json';
 import bn from './locales/bn.json';

 // Set locale translations object
 const messages = { en, bn };

 // configure i18n package
 const i18n = createI18n({
     locale: appConfig.defaultLang, // import.meta.env.VITE_APP_I18N_LOCALE ||
     allowComposition: true,
     fallbackLocale: appConfig.defaultLang, // import.meta.env.VITE_APP_I18N_FALLBACK_LOCALE ||
     messages
 });

 // load helper
 import helpers from './helpers/common';

 // load package
 import router from '@/router';
 import store from '@/store';

 // load layouts
 import AdminLayout from './layouts/AdminLayout.vue';

 // init
 const app = createApp(AdminLayout);
 app.use(appConfig)
 app.use(i18n)
 app.use(Toast, toastOptions)
 app.use(VueSweetalert2)
 app.use(helpers)
 app.use(router)
 app.use(store)

 // get user auth status
 axios.interceptors.response.use(null, error => {
     // if get 401 then this user unauthonticated
     if (error.response.status == 401) {
         store.dispatch('auth/logout');
     }
     return Promise.reject(error);
 });

 app.mount('#app');