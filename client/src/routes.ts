import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';
import Home from './views/Home.vue';
import Login from './views/Login.vue';
import Dashboard from './views/Dashboard.vue';
import Register from './views/Register.vue';
import Inventories from './views/Inventories.vue';

const routes: Array<RouteRecordRaw> = [
    {
        path: '/',
        name: 'home',
        component: Home,
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
    },
    {
        path: '/inventories',
        name: 'inventories',
        component: Inventories,
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
    },
];

const router = createRouter({
    history: createWebHistory(''),
    routes,
});

// router.beforeEach(() => {});

export default router;
