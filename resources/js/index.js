import { createRouter, createWebHistory } from 'vue-router';


import dashboard from "./components/backend/dashboard.vue";

const routes = [
    { path: '/', redirect:'/' },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
