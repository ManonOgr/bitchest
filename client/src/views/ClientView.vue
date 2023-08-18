<template>
  <v-app>
    <sidebar-nav-client></sidebar-nav-client>

    <v-app-bar app>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>Données Personnelles (Client)</v-toolbar-title>
    </v-app-bar>

    <v-main>
      <v-container>
        <dashboard-client></dashboard-client>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import SidebarNavClient from '@/components/SideBarNavClient.vue';
import DashboardClient from '@/components/DashboardClient.vue';

export default {
  components: {
    SidebarNavClient, 
    DashboardClient
  },
  data() {
    return {
      drawer: false
    };
  }
};



</script>











