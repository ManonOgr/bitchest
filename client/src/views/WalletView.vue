<template>
    <v-app>
      <sidebar-nav-client></sidebar-nav-client>
  
      <v-app-bar app>
        <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
        <v-toolbar-title>Portefeuille</v-toolbar-title>
      </v-app-bar>
  
      <v-main>
        <v-container>
    
  <div>
    <div class="container-tab">
      <v-table height="300px">
      <thead>
        <tr>
          <th>Mes cryptos</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>liste des cryptos d'un client</td>
        </tr>
      </tbody>
    </v-table>
    </div>

  </div>

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
  
  
  
  <style>
.title {
  display: flex;
  align-items: center;
  flex-direction: column;
  margin-top: 50px;
}

.container-tab{
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: 50px;
}
</style>
  
  
  
  
  
  
  
  