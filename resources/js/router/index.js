import { createWebHistory, createRouter } from 'vue-router';
import DefaultLayout from "../views/layouts/DefaultLayout";

const routes = [
    {
        path: '/',
        name: 'ChatsList',
        component: () => import('./../views/ChatsList'),
        meta: {
            layout: DefaultLayout,
            auth: true
        },
    },
    {
        path: '/chat/:id',
        name: 'Chat',
        component: () => import('./../views/Chat'),
        meta: {
            layout: DefaultLayout,
            auth: true
        },
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import('./../views/Login'),
    },
    {
        path: '/registration',
        name: 'Registration',
        component: () => import('./../views/Registration'),
    },
    {
        path: '/profile',
        name: 'Profile',
        component: () => import('./../views/Profile'),
        meta: {
            layout: DefaultLayout,
            auth: true
        },
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Редирект на страницу авторизации
router.beforeEach((to, from, next) => {
    const userAuth = localStorage.getItem('user_token');
    const requireAuth = to.matched.some(record => record.meta.auth);

    if(requireAuth && !userAuth){
        next('/login');
    }else if(!requireAuth && userAuth){
        next('/');
    }else{
        next();
    }

});




export default router;
