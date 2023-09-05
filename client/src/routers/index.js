// Import necessary functions from Vue Router library
import { createRouter, createWebHistory } from "vue-router";

// Import various view components
import LoginView from "@/views/LoginView.vue";
import AdminView from "@/views/AdminView.vue";
import ClientView from "@/views/ClientView.vue";
import WalletView from "@/views/WalletView.vue";
import RatesCryptosClientView from "@/views/RatesCryptosClientView.vue";
import RatesCryptosAdminView from "@/views/RatesCryptosAdminView.vue";
import CustomersManagementView from "@/views/CustomersManagementView.vue";
import AddUsersView from "@/views/AddUsersView.vue";
import GraphView from "@/views/GraphView.vue";

// Create a router instance
const router = createRouter({
  history: createWebHistory(), // Use web history mode
  routes: [
    // Route for the login page
    {
      path: "/",
      name: "login",
      component: LoginView,
    },
    // Route for the admin dashboard
    {
      path: "/dashboardadmin",
      name: "dashboardadmin",
      component: AdminView,
      meta: { requiresAuth: true, roles: ["admin"] }, // Additional metadata for authentication and authorization
    },
    // Route for the client dashboard
    {
      path: "/dashboardclient",
      name: "dashboardclient",
      component: ClientView,
      meta: { requiresAuth: true, roles: ["client"] }, // Additional metadata for authentication and authorization
    },
    // Route for the wallet page
    {
      path: "/wallet",
      name: "wallet",
      component: WalletView,
      meta: { requiresAuth: true, roles: ["client"] }, // Additional metadata for authentication and authorization
    },
    // Route for the crypto rates page for clients
    {
      path: "/ratesclient",
      name: "ratesclient",
      component: RatesCryptosClientView,
      meta: { requiresAuth: true, roles: ["client"] }, // Additional metadata for authentication and authorization
    },
    // Route for the crypto rates page for admins
    {
      path: "/ratesadmin",
      name: "ratesadmin",
      component: RatesCryptosAdminView,
      meta: { requiresAuth: true, roles: ["admin"] }, // Additional metadata for authentication and authorization
    },
    // Route for managing customers (admin only)
    {
      path: "/customers",
      name: "customers",
      component: CustomersManagementView,
      meta: { requiresAuth: true, roles: ["admin"] }, // Additional metadata for authentication and authorization
    },
    // Route for adding a new user
    {
      path: "/add-user",
      name: "adduser",
      component: AddUsersView,
    },
      // Route for graphcrypto
      {
        path: "/graphcrypto",
        name: "graphcrypto",
        component: GraphView,
      },
  ],
});

// Export the router instance
export default router;
