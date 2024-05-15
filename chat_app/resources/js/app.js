import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia'
import store from '@/store';
import router from '@/router';
import BootstrapVue3 from 'bootstrap-vue-3';
import Toast from "vue-toastification";
// Import the CSS or use your own!
import 'vue-toastification/dist/index.css';

import App from '@/App.vue';

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue-3/dist/bootstrap-vue-3.css';
import '@/assets/css/styles.css';
import '@/assets/css/styles.css';

const optionsToast = {
    // You can set your default options here
};


createApp(App)
    .use(store)
    .use(createPinia())
    .use(router)
    .use(BootstrapVue3)
    .use(Toast, optionsToast)
    .mount('#app');

