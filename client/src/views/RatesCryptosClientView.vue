<template>
  <!-- Vue app container -->
  <v-app>
    <!-- Sidebar navigation component -->
    <sidebar-nav-client></sidebar-nav-client>

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
              <th class="text-left">Achat</th>
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
              <td>
                <v-btn
                  class="btn"
                  color="pink"
                  @click="openPurchasePopup(crypto)"
                  v-if="isUserAuthenticated"
                  >Achat</v-btn
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
    <!-- Popup de validation d'achat -->
    <v-dialog v-model="purchaseDialog">
      <v-card>
        <v-card-title>
          Sélectionnez la quantité d'achat pour {{ selectedCrypto.name }}
        </v-card-title>
        <v-card-text>
          <v-text-field
            v-model="selectedQuantity"
            label="Quantité"
          ></v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-btn
            color="primary"
            @click="confirmPurchase"
            v-if="isUserAuthenticated"
            >Valider</v-btn
          >
          <v-btn color="red" @click="cancelPurchase">Annuler</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Purchase confirmation message -->
    <v-snackbar
      v-if="purchaseSuccessMessage"
      v-model="purchaseSuccessMessage"
      timeout="2000"
      color="success"
    >
      {{ purchaseSuccessMessage }}
    </v-snackbar>
  </v-app>
</template>

<script>
import axios from "axios";
import SidebarNavClient from "@/components/SideBarNavClient.vue";
import CryptoChartDialog from "@/components/CryptoChartDialog";

export default {
  components: {
    SidebarNavClient,
    CryptoChartDialog,
  },
  computed: {
    isUserAuthenticated() {
      return !!this.$store.state.userData;
    },
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
      purchaseSuccessMessage: null,
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
        const response = await axios.get(URL, { withCredentials: true });
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
    openPurchasePopup(crypto) {
      // Open purchase dialog for the selected crypto
      this.selectedCrypto = crypto;
      this.selectedQuantity = 0;
      this.purchaseDialog = true;
    },
    async confirmPurchase() {
      // Confirm purchase of crypto
      if (this.selectedQuantity <= 0) {
        return;
      }

      const minSellingPrice = 1000;
      const maxSellingPrice = 10000;

      const minPurchasePrice = 1000;
      const maxPurchasePrice = 20000;

      const randomPurchasePrice =
        Math.random() * (maxSellingPrice - minSellingPrice) + minSellingPrice;

      const randomSellingPrice =
        Math.random() * (maxPurchasePrice - minPurchasePrice) +
        minPurchasePrice;

      const transactionData = {
        currency_id: this.selectedCrypto.id,
        quantity: this.selectedQuantity,
        purchase_price: randomPurchasePrice,
        selling_price: randomSellingPrice,
      };

      try {
        const response = await axios.post("/api/purchase", transactionData, {
          withCredentials: true,
        });

        if (response.status === 200) {
          this.selectedQuantity = 0;
          this.purchaseDialog = false;
          this.purchaseSuccessMessage = "Achat réussie";
          setTimeout(() => {
            this.purchaseSuccessMessage = null;
          }, 2000);
        } else {
          console.error("Erreur lors de la transaction", response.data);
        }
      } catch (error) {
        console.error("Erreur lors de la transaction", error);
      }
    },
    cancelPurchase() {
      this.purchaseDialog = false;
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
