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
      <v-toolbar-title>Cours des cryptos</v-toolbar-title>
    </v-app-bar>

    <!-- Main content area -->
    <v-main>
      <v-container>
        <!-- Table displaying crypto data -->
        <v-table>
          <thead>
            <tr>
              <th class="text-left">Id</th>
              <th class="text-left">Nom</th>
              <th class="text-left">Cours</th>
            </tr>
          </thead>
          <tbody>
            <!-- Loop through cryptos and display data -->
            <tr v-for="crypto in cryptos" :key="crypto">
              <td>{{ crypto.id }}</td>
              <td>{{ crypto.name }}</td>
              <td>{{ getCryptoQuoting(crypto) }} €</td>
            </tr>
          </tbody>
        </v-table>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import axios from "axios"; // Importing axios for making API requests
import SidebarNavClient from "@/components/SideBarNavClient.vue"; // Importing the Client-specific SidebarNav component

export default {
  components: {
    SidebarNavClient, // Registering the Client-specific SidebarNav component
  },
  data() {
    return {
      drawer: false, // Data property for controlling the sidebar drawer state
      cryptos: [], // Array to hold crypto data
    };
  },
  mounted() {
    this.fetchCryptos(); // Fetch crypto data when the component is mounted
    this.fetchHistory(); // Fetch crypto history data when the component is mounted
  },
  methods: {
    async fetchCryptos() {
      const URL = "http://localhost:8000/api/currencies"; // API endpoint for fetching crypto data
      try {
        const response = await axios.get(URL);
        this.cryptos = response.data; // Assign fetched data to the cryptos array
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    },
    getCryptoQuoting(crypto) {
      return crypto.history ? crypto.history.quoting : 'N/A'; // Get crypto price from history or show 'N/A'
    },
    async fetchHistory() {
      const URL = "http://localhost:8000/api/history"; // API endpoint for fetching crypto history data
      try {
        const response = await axios.get(URL);
        this.cryptos.forEach((crypto, index) => {
          crypto.history = response.data[index]; // Assign history data to each crypto
        });
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    },
  },
};
</script>
