<template>
  <v-app>
    <sidebar-nav></sidebar-nav>

    <v-app-bar app>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>Données Personnelles (Admin)</v-toolbar-title>
    </v-app-bar>

    <v-main>
      <v-container>
        <dashboard-admin></dashboard-admin>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import SidebarNav from '@/components/SideBarNav.vue';
import DashboardAdmin from '@/components/DashboardAdmin.vue';

export default {
  components: {
    SidebarNav, 
    DashboardAdmin
  },
  data() {
    return {
      drawer: false
    };
  }
};



</script>











