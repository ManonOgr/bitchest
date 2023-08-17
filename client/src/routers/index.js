import { createRouter, createWebHistory } from 'vue-router'


import LoginView from '@/views/LoginView.vue';
import AdminView from '@/views/AdminView.vue';
import ClientView from '@/views/ClientView.vue';



const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView
    },
    {
      path: '/dashboardadmin',
      name: 'dashboardadmin',
      component: AdminView
    },
    {
      path: '/dashboardclient',
      name: 'dashboardclient',
      component: ClientView
    },
    
  ]
})



export default router
