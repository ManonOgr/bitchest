<template>
  <!-- Vue app container -->
  <v-app>
    <!-- Sidebar navigation component -->
    <sidebar-nav></sidebar-nav>

    <!-- App bar for the application -->
    <v-app-bar app>
      <!-- Navigation icon for opening/closing sidebar -->
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>Cours des cryptos</v-toolbar-title>
    </v-app-bar>

    <!-- Main content area -->
    <v-main>
      <v-container>
        <v-table>
          <thead>
            <tr>
              <!-- Table headers -->
              <th class="text-left">Id</th>
              <th class="text-left">Nom</th>
              <th class="text-left">Cours</th>
              <th class="text-left">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="crypto in cryptos" :key="crypto.id">
              <!-- Display crypto data -->
              <td>{{ crypto.id }}</td>
              <td>{{ crypto.name }}</td>
              <td>{{ getCryptoQuoting(crypto) }} €</td>
              <td>
                <!-- Button to view crypto chart -->
                <v-btn
                  class="btn"
                  color="#80CBC4"
                  @click="showCryptoChart(crypto.id)"
                  >Voir les cours</v-btn
                >
              </td>
            </tr>
          </tbody>
        </v-table>
      </v-container>
    </v-main>

    <!-- Dialog component for displaying crypto chart -->
    <crypto-chart-dialog
      v-if="selectedCryptoId !== null"
      :selected-crypto="selectedCrypto"
      @close="closeCryptoChartDialog"
    ></crypto-chart-dialog>
  </v-app>
</template>

<script>
import axios from "axios";
import SidebarNav from "@/components/SideBarNav.vue";
import CryptoChartDialog from "@/components/CryptoChartDialog";

export default {
  components: {
    SidebarNav,
    CryptoChartDialog,
  },
  data() {
    return {
      drawer: false,
      cryptos: [],
      purchaseDialog: false,
      selectedCryptoId: null,
      selectedQuantity: 0,
      transactions: [],
      selectedCrypto: null,
    };
  },
  mounted() {
    // Fetch crypto data and history on component mount
    this.fetchCryptos();
    this.fetchHistory();
  },
  methods: {
    async fetchCryptos() {
      // Fetch cryptocurrency data
      const URL = "http://localhost:8000/api/currencies";
      try {
        const response = await axios.get(URL);
        this.cryptos = response.data;
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    },
    getCryptoQuoting(crypto) {
      // Get crypto quoting or display "N/A" if data is missing
      return crypto.history ? crypto.history.quoting : "N/A";
    },
    async fetchHistory() {
      // Fetch cryptocurrency history data
      const URL = "http://localhost:8000/api/history";
      try {
        const response = await axios.get(URL);
        this.cryptos.forEach((crypto, index) => {
          crypto.history = response.data[index];
        });
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    },
    showCryptoChart(cryptoId) {
      // Open CryptoChartDialog with selected crypto data
      this.selectedCrypto = this.cryptos.find(
        (crypto) => crypto.id === cryptoId
      );
      this.selectedCryptoId = cryptoId;
    },
    closeCryptoChartDialog() {
      // Close CryptoChartDialog
      this.selectedCrypto = null;
      this.selectedCryptoId = null;
    },
  },
};
</script>

<style>
.btn {
  text-align: center;
  margin-bottom: 0px;
  margin-top: 0px;
}
</style>
