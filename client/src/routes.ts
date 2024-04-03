import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';
// import Home from './views/Home.vue';
import Login from './views/Login.vue';
import Dashboard from './views/Dashboard.vue';
// import Register from './views/Register.vue';

const routes: Array<RouteRecordRaw> = [
    // {
    //     path: '/',
    //     name: 'Home',
    //     component: Home,
    // },
    {
        path: '/login',
        name: 'Login',
        component: Login,
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: Dashboard,
    },
    // {
    //     path: '/register',
    //     name: 'Register',
    //     component: Register,
    // },
];

const router = createRouter({
    history: createWebHistory(''),
    routes,
});

export default router;
