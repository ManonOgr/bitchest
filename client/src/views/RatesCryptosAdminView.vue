<template>
  <v-app>
    <sidebar-nav></sidebar-nav>
    <v-app-bar app>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>Cours des cryptos</v-toolbar-title>
    </v-app-bar>
    <v-main>
      <v-container>
        <v-table>
          <thead>
            <tr>
              <th class="text-left">Id</th>
              <th class="text-left">Nom</th>
              <th class="text-left">Cours</th>
              <th class="text-left">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="crypto in cryptos" :key="crypto.id">
              <td>{{ crypto.id }}</td>
              <td>{{ crypto.name }}</td>
              <td>{{ getCryptoQuoting(crypto) }} €</td>
              <td>
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
      purchaseDialog: false, // État de la popup d'achat
      selectedCryptoId: null,
      selectedQuantity: 0,
      transactions: [],
      selectedCrypto: null,
    };
  },
  mounted() {
    this.fetchCryptos();
    this.fetchHistory();
  },
  methods: {
    async fetchCryptos() {
      const URL = "http://localhost:8000/api/currencies";
      try {
        const response = await axios.get(URL);
        this.cryptos = response.data;
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    },
    getCryptoQuoting(crypto) {
      return crypto.history ? crypto.history.quoting : "N/A";
    },
    async fetchHistory() {
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
      // Ouvrir le dialogue CryptoChartDialog avec les données de la crypto sélectionnée
      this.selectedCrypto = this.cryptos.find(
        (crypto) => crypto.id === cryptoId
      );
      this.selectedCryptoId = cryptoId;
    },
    closeCryptoChartDialog() {
      // Fermer le dialogue CryptoChartDialog
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
