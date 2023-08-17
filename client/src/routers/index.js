import { createRouter, createWebHistory } from 'vue-router'


import LoginView from '@/views/LoginView.vue';
import AdminView from '@/views/AdminView.vue';
import ClientView from '@/views/ClientView.vue';
import WalletView from '@/views/WalletView.vue';
import RatesCryptosClientView from '@/views/RatesCryptosClientView.vue';
import RatesCryptosAdminView from '@/views/RatesCryptosAdminView.vue'
import CustomersManagementView from '@/views/CustomersManagementView.vue';



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
    {
      path: '/wallet',
      name: 'wallet',
      component: WalletView
    },
    {
      path: '/ratesclient',
      name: 'ratesclient',
      component: RatesCryptosClientView
    },
    {
      path: '/ratesadmin',
      name: 'ratesadmin',
      component: RatesCryptosAdminView
    },
    {
      path: '/customers',
      name: 'customers',
      component: CustomersManagementView
    },
    
  ]
})



export default router
