<template>
  <v-app>
    <sidebar-nav-client></sidebar-nav-client>
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
              <th class="text-left">Achat</th> <!-- Nouvelle colonne "Achat" -->
            </tr>
          </thead>
          <tbody>
            <tr v-for="crypto in cryptos" :key="crypto.id">
              <td>{{ crypto.id }}</td>
              <td>{{ crypto.name }}</td>
              <td>{{ getCryptoQuoting(crypto) }} €</td>
              <td>
                <v-btn class="btn" color="#80CBC4" @click="showCryptoChart(crypto.id)">Voir les cours</v-btn>
              </td>
              <td>
                <v-btn class="btn" color="pink" @click="openPurchasePopup(crypto)" v-if="isUserAuthenticated">Achat</v-btn>
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
          <v-text-field v-model="selectedQuantity" label="Quantité"></v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="confirmPurchase" v-if="isUserAuthenticated">Valider</v-btn>
          <v-btn color="red" @click="cancelPurchase">Annuler</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
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
      return !!this.$store.state.userData; // Vérifiez si l'utilisateur est authentifié
    },
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
        const response = await axios.get(URL, {withCredentials:true});
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
      this.selectedCrypto = this.cryptos.find((crypto) => crypto.id === cryptoId);
      this.selectedCryptoId = cryptoId;
    },
    closeCryptoChartDialog() {
      // Fermer le dialogue CryptoChartDialog
      this.selectedCrypto = null;
      this.selectedCryptoId = null;
    },
    openPurchasePopup(crypto) {
      // Ouvrir la popup d'achat et définir la crypto sélectionnée
      this.selectedCrypto = crypto;
      this.selectedQuantity = 0; // Réinitialiser la quantité
      this.purchaseDialog = true;
    },
  
    async confirmPurchase() {
  try {
    // Vérifiez d'abord si l'utilisateur est connecté en lisant le token de session depuis localStorage
    const sessionToken = localStorage.getItem('user');

    if (!sessionToken) {
      console.error("L'utilisateur n'est pas connecté.");
      // Vous pouvez afficher un message à l'utilisateur ou le rediriger vers la page de connexion ici
      return;
    }

    console.log("Données de l'utilisateur depuis le store:", this.$store.state.userData);

    // Créez un objet de données pour la transaction
    const transactionData = {
      crypto_id: this.selectedCrypto.id,
      quantity: this.selectedQuantity,
      purchase_price: this.getCryptoQuoting(this.selectedCrypto),
      // Ajoutez d'autres données de transaction si nécessaire
    };

    const headers = {
      Authorization: `Bearer ${sessionToken}`,
    };

    // Ensuite, utilisez ces en-têtes dans votre requête Axios
    const response = await axios.post("http://localhost:8000/api/purchase", transactionData,  { withCredentials:true}, {
      headers: headers,  
    });

    // Traitez la réponse si nécessaire
    console.log("Transaction enregistrée avec succès", response.data);

    // Après l'achat, fermez la popup
    this.purchaseDialog = false;
  } catch (error) {
    console.error("Erreur lors de l'enregistrement de la transaction:", error);
  }
},




    cancelPurchase() {
      // Annuler l'achat et fermer la popup
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
