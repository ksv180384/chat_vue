import { createWebHistory, createRouter } from 'vue-router';

import AuthLayout from '@/views/Layouts/AuthLayout.vue';
import DefaultLayout from '@/views/Layouts/DefaultLayout.vue';
import store from '@/store';

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: () => import('@/views/Login.vue'),
        meta: {
            layout: AuthLayout,
            auth: false
        },
    },
    {
        path: '/registration',
        name: 'Registration',
        component: () => import('@/views/Registration.vue'),
        meta: {
            layout: AuthLayout,
            auth: false
        },
    },
    // Chat
    {
        path: '/',
        name: 'ChatsList',
        component: () => import('@/views/Chat/ChatsList.vue'),
        meta: {
            layout: DefaultLayout,
            auth: true
        },
    },
    {
        path: '/chat/:id',
        name: 'Chat',
        component: () => import('@/views/Chat/Chat.vue'),
        meta: {
            layout: DefaultLayout,
            auth: true
        },
    },
    {
        path: '/chat/:id/settings',
        name: 'ChatUserSettings',
        component: () => import('@/views/Chat/ChatUserSettings.vue'),
        meta: {
            layout: DefaultLayout,
            auth: true
        },
    },
    // User
    {
        path: '/profile',
        name: 'Profile',
        component: () => import('@/views/User/Profile.vue'),
        meta: {
            layout: DefaultLayout,
            auth: true
        },
    },
    {
        path: "/:pathMatch(.*)*",
        component: () => import('@/views/PageNotFound.vue'),
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
    store.commit('setIsSiteNotWork', false); // сбрасываем отображение страницы с ошибкой
    // Редирект на страницу авторизации
    const userAuth = localStorage.getItem('user_token');
    const requireAuth = to.matched.some(record => record.meta.auth);

    if(requireAuth && !userAuth){
        next('/login');
    }else{
        next();
    }

    // Подстановка layout поумолчанию
    const layout = to ? to?.meta?.layout : null;
    to.meta.layout = setDefaultLayout(layout);

});

const setDefaultLayout = (layout) => {
    if(!layout){
        return AuthLayout
    }

    return layout;
}


export default router;
