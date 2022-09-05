import { createWebHistory, createRouter } from 'vue-router';

import AuthLayout from "../views/Layouts/AuthLayout";
import DefaultLayout from "../views/Layouts/DefaultLayout";

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: () => import('./../views/Login'),
        meta: {
            layout: AuthLayout,
            auth: false
        },
    },
    {
        path: '/registration',
        name: 'Registration',
        component: () => import('./../views/Registration'),
        meta: {
            layout: AuthLayout,
            auth: false
        },
    },
    // Chat
    {
        path: '/',
        name: 'ChatsList',
        component: () => import('./../views/Chat/ChatsList'),
        meta: {
            auth: true
        },
    },
    {
        path: '/chat/:id',
        name: 'Chat',
        component: () => import('../views/Chat/Chat'),
        meta: {
            auth: true
        },
    },
    {
        path: '/chat/:id/settings',
        name: 'ChatUserSettings',
        component: () => import('./../views/Chat/ChatUserSettings'),
        meta: {
            auth: true
        },
    },
    // User
    {
        path: '/profile',
        name: 'Profile',
        component: () => import('./../views/User/Profile'),
        meta: {
            auth: true
        },
    },
    {
        path: "/:pathMatch(.*)*",
        component: () => import('./../views/PageNotFound'),
        meta: {
            layout: AuthLayout,
        },
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    // Редирект на страницу авторизации
    const userAuth = localStorage.getItem('user_token');
    const requireAuth = to.matched.some(record => record.meta.auth);

    if(requireAuth && !userAuth){
        next('/login');
    }else{
        next();
    }

    // Подстановка layout поумолчанию
    to.meta.layout = setDefaultLayout(to.meta.layout);

});

const setDefaultLayout = (layout) => {
    if(!layout){
        return DefaultLayout
    }

    return layout;
}


export default router;
