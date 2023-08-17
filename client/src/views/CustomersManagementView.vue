<template>
    <v-app>
      <sidebar-nav></sidebar-nav>
  
      <v-app-bar app>
        <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
        <v-toolbar-title>Portefeuille</v-toolbar-title>
      </v-app-bar>
  
      <v-main>
        <v-container>
          <p>Contenu Page Wallet</p>
        </v-container>
      </v-main>
    </v-app>
  </template>
  
  <script>
  import SidebarNav from '@/components/SideBarNav.vue';
  
  export default {
    components: {
      SidebarNav, 
    },
    data() {
      return {
        drawer: false
      };
    }
  };
  
  
  
  </script>
  
  
  
  
  
  
  
  
  
  
  
  