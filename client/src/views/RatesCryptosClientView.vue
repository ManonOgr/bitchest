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
              <th class="text-left">Action</th> <!-- Add a new column for the "Purchase" button -->
            </tr>
          </thead>
          <tbody>
            <!-- Loop through cryptos and display data -->
            <tr v-for="crypto in cryptos" :key="crypto">
              <td>{{ crypto.id }}</td>
              <td>{{ crypto.name }}</td>
              <td>{{ getCryptoQuoting(crypto) }} €</td>
              <td>
                <!-- Add a "Purchase" button that triggers the dialog -->
                <v-btn color="purple" @click="openPurchaseDialog(crypto)">Achat</v-btn>
              </td>
            </tr>
          </tbody>
        </v-table>
      </v-container>
    </v-main>

    <!-- Popup dialog for selecting quantity -->
    <v-dialog v-model="purchaseDialog" max-width="500">
      <v-card>
        <v-card-title>
          Sélectionner la quantité à acheter pour {{ selectedCrypto ? selectedCrypto.name : '' }}
        </v-card-title>
        <v-card-text>
          <v-text-field v-model="selectedQuantity" label="Quantité" type="number"></v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-btn color="purple" @click="confirmPurchase">Acheter</v-btn>
          <v-btn @click="closePurchaseDialog">Annuler</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
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
      purchaseDialog: false, // Flag to control the purchase dialog visibility
      selectedCrypto: null, // Store the selected crypto for purchase
      selectedQuantity: 0, // Store the selected quantity for purchase
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
      return crypto.history ? crypto.history.quoting : "N/A"; // Get crypto price from history or show 'N/A'
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
    openPurchaseDialog(crypto) {
      // Set the selected crypto and open the purchase dialog
      this.selectedCrypto = crypto;
      this.purchaseDialog = true;
    },
    async confirmPurchase() {
  try {
    if (!this.selectedCrypto) {
      console.error("Sélectionnez une crypto avant d'acheter.");
      return;
    }

    // Assurez-vous que selectedCrypto contient l'ID de la crypto que vous souhaitez acheter
    const cryptoId = this.selectedCrypto.id;

    const response = await axios.post('http://localhost:8000/api/buy-crypto', {
      crypto_id: cryptoId, // Assurez-vous d'inclure le champ crypto_id avec la valeur correcte
      quantity: this.selectedQuantity,
    });

    // Gérez la réponse de votre API ici, par exemple, affichez un message de confirmation.
    console.log("Réponse de l'API :", response.data);

    // Fermez la boîte de dialogue et réinitialisez les valeurs
    this.purchaseDialog = false;
    this.selectedCrypto = null;
    this.selectedQuantity = 0;
  } catch (error) {
    // Gérez les erreurs ici, par exemple, affichez un message d'erreur.
    console.error("Erreur lors de l'achat :", error);
  }
},

    closePurchaseDialog() {
      this.purchaseDialog = false;
      this.selectedCrypto = null;
      this.selectedQuantity = 0;
    },
  },
};
</script>
