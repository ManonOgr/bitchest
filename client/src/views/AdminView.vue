<template>
  <!-- Vue app container -->
  <v-app>
    <!-- Sidebar navigation component -->
    <sidebar-nav></sidebar-nav>
    <!-- Main content area -->
    <v-main>
      <!-- Container for the admin dashboard component -->
      <v-container>
        <dashboard-admin></dashboard-admin>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import SidebarNav from '@/components/SideBarNav.vue'; // Importing the SidebarNav component
import DashboardAdmin from '@/components/DashboardAdmin.vue'; // Importing the DashboardAdmin component

export default {
  components: {
    SidebarNav, // Registering the SidebarNav component
    DashboardAdmin // Registering the DashboardAdmin component
  },
  data() {
    return {
      drawer: false // Data property for controlling the sidebar drawer state
    };
  }
};
</script>
