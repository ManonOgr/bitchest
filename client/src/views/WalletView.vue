<template>
    <v-app>
      <sidebar-nav-client></sidebar-nav-client>
  
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
  import SidebarNavClient from '@/components/SideBarNavClient.vue';
  
  export default {
    components: {
      SidebarNavClient, 
    },
    data() {
      return {
        drawer: false
      };
    }
  };
  
  
  
  </script>
  
  
  
  
  
  
  
  
  
  
  
  