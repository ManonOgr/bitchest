<template>
  <!-- Vue app container -->
  <v-app>
    <!-- Sidebar navigation component -->
    <sidebar-nav></sidebar-nav>

    <!-- App bar for the application -->
    <v-app-bar app>
      <!-- Navigation icon for opening/closing sidebar -->
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <!-- Title for the app bar -->
      <v-toolbar-title>Données personnelles (Admin)</v-toolbar-title>
    </v-app-bar>

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
