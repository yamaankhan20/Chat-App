
import './bootstrap';
import { createApp } from 'vue';
// import router from './index.js';



const app = createApp({});

import login from './components/login.vue';
import register from './components/register.vue';
import dashboard from './components/backend/dashboard.vue';
app.component('login', login);
app.component('register', register);
app.component('dashboard', dashboard);

const API_URL = 'http://localhost:8080/api/chat/';
app.config.globalProperties.$apiUrl = API_URL;

// app.use(router);
app.mount('#app');

