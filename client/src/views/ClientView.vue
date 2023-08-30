<template>
  <!-- Vue app container -->
  <v-app>
    <!-- Client-specific sidebar navigation component -->
    <sidebar-nav-client></sidebar-nav-client>

    <!-- App bar for the application -->
    <v-app-bar app>
      <!-- Navigation icon for opening/closing sidebar -->
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <!-- Title for the app bar -->
      <v-toolbar-title>Données personnelles(Client)</v-toolbar-title>
    </v-app-bar>

    <!-- Main content area -->
    <v-main>
      <!-- Container for the client dashboard component -->
      <v-container>
        <dashboard-client></dashboard-client>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import SidebarNavClient from '@/components/SideBarNavClient.vue'; // Importing the Client-specific SidebarNav component
import DashboardClient from '@/components/DashboardClient.vue'; // Importing the Client-specific Dashboard component

export default {
  components: {
    SidebarNavClient, // Registering the Client-specific SidebarNav component
    DashboardClient // Registering the Client-specific Dashboard component
  },
  data() {
    return {
      drawer: false // Data property for controlling the sidebar drawer state
    };
  }
};
</script>
