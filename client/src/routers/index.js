import { createRouter, createWebHistory } from 'vue-router'


import LoginView from '@/views/LoginView.vue';
import AdminView from '@/views/AdminView.vue';
import ClientView from '@/views/ClientView.vue';
import WalletView from '@/views/WalletView.vue';
import RatesCryptosClientView from '@/views/RatesCryptosClientView.vue';
import RatesCryptosAdminView from '@/views/RatesCryptosAdminView.vue'
import CustomersManagementView from '@/views/CustomersManagementView.vue';
import AddUsersView from '@/views/AddUsersView.vue';
import UpdateUsersView from '@/views/UpdateUsersView.vue';



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
      component: AdminView,
      meta: { requiresAuth: true, roles: ['admin'] }
    },
    {
      path: '/dashboardclient',
      name: 'dashboardclient',
      component: ClientView,
      meta: { requiresAuth: true, roles: ['client'] }
    },
    {
      path: '/wallet',
      name: 'wallet',
      component: WalletView,
      meta: { requiresAuth: true, roles: ['client'] }
    },
    {
      path: '/ratesclient',
      name: 'ratesclient',
      component: RatesCryptosClientView,
      meta: { requiresAuth: true, roles: ['client'] }
    },
    {
      path: '/ratesadmin',
      name: 'ratesadmin',
      component: RatesCryptosAdminView,
      meta: { requiresAuth: true, roles: ['admin'] }
    },
    {
      path: '/customers',
      name: 'customers',
      component: CustomersManagementView,
      meta: { requiresAuth: true, roles: ['admin'] }
    },
    {
      path: '/add-user',
      name: 'adduser',
      component: AddUsersView,
    },
    {
        path: '/update-user/:id', // Utilisez :id pour capturer l'ID de l'utilisateur
        name: 'updateuser',
        component: UpdateUsersView,
    }
    
  ]
})
export default router
