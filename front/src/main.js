import { createApp } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

import router from './router';
import library from './font-awesome/index';

import './style.css';
import App from './App.vue'

createApp(App)
    .use(router)
    .component('font-awesome-icon', FontAwesomeIcon)
    .mount('#app')