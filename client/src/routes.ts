import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';
import useAuthStore from '../src/stores/auth';

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
        meta: { protected: 0 },
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: { protected: -1 },
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: { protected: -1 },
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: Dashboard,
        meta: { protected: 1 },
    },
    {
        path: '/inventories',
        name: 'inventories',
        component: Inventories,
        meta: { protected: 1 },
    },
];

// protected
// 0, means it is public and does not matter user is logged in or not
// 1, means it is protected and user should be logged in
// -1, means it is not protected and user should not be logged in

const router = createRouter({
    history: createWebHistory(''),
    routes,
});

router.beforeEach(async (to, from, next) => {
    const _protected = to.meta.protected;
    const auth = useAuthStore();

    while (auth.isRefreshing) {
        await new Promise((resolve) => setTimeout(resolve, 100));
    }

    const isAuthenticated = auth.token !== '';

    console.log(auth.token);

    if (_protected == 1 && !isAuthenticated) {
        next({ name: 'login' });
    } else if (_protected == -1 && isAuthenticated) {
        next({ name: 'dashboard' });
    } else {
        next();
    }
});

export default router;
