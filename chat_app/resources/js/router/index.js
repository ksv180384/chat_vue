import { createWebHistory, createRouter } from 'vue-router';
import api from '@/services/api';
import { useAuthUserStore } from '@/store/auth_user.js';
import { usePageStore } from '@/store/page.js';

import AuthLayout from '@/views/Layouts/AuthLayout.vue';
import DefaultLayout from '@/views/Layouts/DefaultLayout.vue';
//import {login} from "@/services/user_service.js";


const routes = [
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/Login.vue'),
    meta: {
      layout: AuthLayout,
      auth: false
    },
  },
  {
    path: '/registration',
    name: 'registration',
    component: () => import('@/views/Registration.vue'),
    meta: {
      layout: AuthLayout,
      auth: false
    },
  },
  // Chat
  {
    path: '/',
    name: 'chats-list',
    component: () => import('@/views/Chat/ChatsList.vue'),
    meta: {
      layout: DefaultLayout,
      auth: true
    },
  },
  {
    path: '/chat/:id',
    name: 'chat',
    component: () => import('@/views/Chat/Chat.vue'),
    meta: {
      layout: DefaultLayout,
      auth: true
    },
  },
  {
    path: '/chat/:id/settings',
    name: 'chat.user-settings',
    component: () => import('@/views/Chat/ChatUserSettings.vue'),
    meta: {
      layout: DefaultLayout,
      auth: true
    },
  },
  // User
  {
    path: '/profile',
    name: 'profile',
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

router.beforeEach(async (to, from, next) => {

  const isPageNeedAuth = to.matched.some(record => record.meta.auth);
  const authUserStore = useAuthUserStore();
  const pageStore = usePageStore();

  const res = await api.get(`/page${to.path}`);
  if(res?.pages_info?.auth_data){
    authUserStore.setUser(res.pages_info.auth_data);
  }
  pageStore.clearData();
  if(res.data){
    pageStore.setPageData(res.data);
  }
  if(isPageNeedAuth && !authUserStore.auth_data){
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
