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
      <v-toolbar-title>Portefeuille</v-toolbar-title>
    </v-app-bar>

    <!-- Main content area -->
    <v-main>
      <v-container>
        <div>
          <div class="container-tab">
            <!-- Table displaying user's crypto data -->
            <v-table height="300px">
              <thead>
                <tr>
                  <th>Mes Cryptos</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>List of cryptocurrencies for a client</td>
                </tr>
              </tbody>
            </v-table>
          </div>
        </div>

        <p>Wallet Page Content</p>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import SidebarNavClient from '@/components/SideBarNavClient.vue'; // Importing the Client-specific SidebarNav component

export default {
  components: {
    SidebarNavClient, // Registering the Client-specific SidebarNav component
  },
  data() {
    return {
      drawer: false, // Data property for controlling the sidebar drawer state
    };
  },
};
</script>

<style>
.title {
  display: flex;
  align-items: center;
  flex-direction: column;
  margin-top: 50px;
}

.container-tab {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: 50px;
}
</style>
