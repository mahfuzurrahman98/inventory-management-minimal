import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router';
import useAuthStore from '../src/stores/auth';

import Login from './views/Login.vue';
import Register from './views/Register.vue';
import Inventories from './views/Inventories.vue';
import Items from './views/Items.vue';
import CreateItem from './views/CreateItem.vue';
import EditItem from './views/EditItem.vue';
import NotFound from './views/NotFound.vue';

const routes: Array<RouteRecordRaw> = [
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
        path: '/inventories',
        name: 'inventories',
        component: Inventories,
        meta: { protected: 1 },
    },
    {
        path: '/items',
        name: 'items',
        component: Items,
        meta: { protected: 1 },
    },
    {
        path: '/items/create',
        name: 'create_item',
        component: CreateItem,
        meta: { protected: 1 },
    },
    {
        path: '/items/:id/edit',
        name: 'edit_item',
        component: EditItem,
        meta: { protected: 1 },
    },
    {
        path: '/',
        redirect: '/inventories',
        meta: { protected: 1 },
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: NotFound,
        meta: { protected: 0 },
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

    from;

    const isAuthenticated = auth.token !== '';

    console.log(auth.token);

    if (_protected == 1 && !isAuthenticated) {
        next({ name: 'login' });
    } else if (_protected == -1 && isAuthenticated) {
        next({ name: 'inventories' });
    } else {
        next();
    }
});

export default router;
