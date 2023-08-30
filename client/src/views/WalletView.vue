<template>
  <v-app>
    <!-- Client-specific sidebar navigation component -->
    <SideBarNavClient />

    <v-app-bar app>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>Wallet</v-toolbar-title>
    </v-app-bar>

    <v-main>
      <v-container>
        <h1>List of My Cryptocurrencies</h1>
        <v-responsive>
          <v-table height="300px">
            <thead>
              <tr>
                <th class="text-left id-column">ID Crypto</th>
                <th class="text-left">Nom Crypto</th>
                <th class="text-left">Quantité</th>
                <th class="text-left">Date d'achat</th>
              </tr>
            </thead>
            <tbody>
              <!-- Loop through user's transactions and display them -->
              <tr v-for="transaction in userTransactions" :key="transaction.id">
                <td class="text-left">{{ transaction.currency_id }}</td>
                <td class="text-left">{{ transaction.currency_name }}</td>
                <td class="text-left">{{ transaction.quantity }}</td>
                <td class="text-left">{{ transaction.purchase_date }}</td>
              </tr>
            </tbody>
          </v-table>
        </v-responsive>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
// Import necessary components and libraries
import SideBarNavClient from "@/components/SideBarNavClient.vue"; // Import the sidebar navigation component
import axios from "axios"; // Import the Axios library for making HTTP requests

export default {
  data() {
    return {
      userTransactions: [], // Initialize an empty array to store user transactions
    };
  },
  async created() {
    try {
      // Check if user data exists in the store
      if (this.$store.state.userData) {
        const userId = this.$store.state.userData.id; // Extract the user's ID from the store
        const response = await axios.get(
          `http://127.0.0.1:8000/api/user-transactions/${userId}`
        ); // Make an API request to fetch user transactions based on the user's ID
        this.userTransactions = response.data.transactions; // Store the fetched transactions in the userTransactions array
      } else {
        console.log("User data is not available yet.");
      }
    } catch (error) {
      console.log("Error during request: ", error); // Log an error if the API request encounters an issue
    }
  },
  components: { SideBarNavClient }, // Use the imported sidebar navigation component
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
