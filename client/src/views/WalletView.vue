<template>
  <v-app>
    <!-- Client-specific sidebar navigation component -->
    <SideBarNavClient />

    <v-app-bar app>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>Portefeuille</v-toolbar-title>
    </v-app-bar>

    <v-main>
      <v-container>
        <h1>Liste de mes Cryptos</h1>
        <h2>Solde mes cryptos en Euros: {{ formattedTotalBalanceEuros }}</h2>
        <h2>Solde de mon compte en Euros: {{ formattedFakeAccountBalance }}</h2>
        <v-responsive>
          <v-table height="300px">
            <thead>
              <tr>
                <th class="text-left id-column">Crypto ID</th>
                <th class="text-left">Nom de la crypto</th>
                <th class="text-left">Quantité</th>
                <th class="text-left">Date d'achat</th>
                <th class="text-left">Prix d'achat</th>
                <th class="text-left">Prix de vente</th>
                <th class="text-left">Plus Value</th>
                <!-- New column for Plus Value -->
                <th class="text-left">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="transaction in userTransactions" :key="transaction.id">
                <td class="text-left">{{ transaction.currency_id }}</td>
                <td class="text-left">{{ transaction.currency_name }}</td>
                <td class="text-left">{{ transaction.quantity }}</td>
                <td class="text-left">
                  {{ formatPurchaseDate(transaction.purchase_date) }}
                </td>
                <td class="text-left">{{ transaction.purchase_price }}</td>
                <td class="text-left">{{ transaction.selling_price }}</td>
                <td
                  class="text-left"
                  :class="{
                    'text-red': isNegativeCapitalGain(transaction),
                    'text-green': isPositiveCapitalGain(transaction),
                  }"
                >
                  {{ formattedCapitalGain(transaction) }}
                  <span
                    v-if="isNegativeCapitalGain(transaction)"
                    class="arrow-down"
                    >▼</span
                  >
                  <span
                    v-if="isPositiveCapitalGain(transaction)"
                    class="arrow-up"
                    >▲</span
                  >
                </td>
                <td class="text-left">
                  <v-btn
                    class="btn"
                    color="primary"
                    @click="confirmSellCrypto(transaction)"
                  >
                    Vendre
                  </v-btn>
                </td>
              </tr>
            </tbody>
          </v-table>
        </v-responsive>
      </v-container>
    </v-main>

    <!-- Boîte de dialogue de confirmation de vente -->
    <v-dialog v-model="sellDialog" max-width="400px">
      <v-card>
        <v-card-title>Confirmation de la vente</v-card-title>
        <v-card-text>
          Êtes-vous sûr de vouloir vendre cette crypto ?
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="sellCrypto(transactionToSell)"
            >Confirmer</v-btn
          >
          <v-btn color="error" @click="cancelSellCrypto">Annuler</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-app>
</template>

<script>
import SideBarNavClient from "@/components/SideBarNavClient.vue";
import axios from "axios";

export default {
  data() {
    return {
      userTransactions: [],
      totalBalanceEuros: 0,
      fakeAccountBalance: 0,
      sellDialog: false,
      transactionToSell: null,
      sellingInProgress: false, // État de la vente en cours
    };
  },
  computed: {
    formattedTotalBalanceEuros() {
      return new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: "EUR",
      }).format(this.totalBalanceEuros);
    },
    formattedFakeAccountBalance() {
      return new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: "EUR",
      }).format(this.fakeAccountBalance);
    },
  },
  async created() {
    try {
      if (this.$store.state.userData) {
        const userId = this.$store.state.userData.id;

        // Utilisez l'ID de l'utilisateur pour générer une donnée fictive unique
        const randomSeed = userId * 1000; // Utilisez une valeur basée sur l'ID de l'utilisateur

        // Générez une donnée fictive unique pour cet utilisateur
        this.fakeAccountBalance = Math.floor(
          Math.random() * (1000 - 1000 + 1) + 1000 + randomSeed
        );

        const response = await axios.get(
          `http://127.0.0.1:8000/api/user-transactions/${userId}`
        );
        this.userTransactions = response.data.transactions;

        // Calculate the total balance in euros
        this.totalBalanceEuros = await this.calculateTotalBalanceEuros();
      } else {
        console.log("User data is not available yet.");
      }
    } catch (error) {
      console.log("Error during request: ", error);
    }
  },
  methods: {
    formatPurchaseDate(dateString) {
      if (!dateString) return "";

      const options = { year: "numeric", month: "2-digit", day: "2-digit" };
      const date = new Date(dateString);
      return date.toLocaleDateString("fr-FR", options);
    },
    async calculateTotalBalanceEuros() {
      let totalBalanceEuros = 0;
      for (const transaction of this.userTransactions) {
        // Calculez le total de chaque transaction en euros
        const transactionTotal =
          transaction.quantity * transaction.purchase_price;
        totalBalanceEuros += transactionTotal;
      }
      return totalBalanceEuros;
    },

    confirmSellCrypto(transaction) {
      this.transactionToSell = transaction;
      this.sellDialog = true;
    },

    async sellCrypto(transaction) {
      try {
        // Commencez la vente (par exemple, suppression de la transaction)
        const index = this.userTransactions.findIndex(
          (t) => t.id === transaction.id
        );
        if (index !== -1) {
          this.userTransactions.splice(index, 1);
        }

        // Mettez à jour le solde fictif du compte
        if (
          !isNaN(this.fakeAccountBalance) &&
          !isNaN(transaction.selling_price)
        ) {
          this.fakeAccountBalance += parseFloat(transaction.selling_price);
        } else {
          console.log("Invalid value for fakeAccountBalance or selling_price.");
        }

        // Recalculez le solde total en euros
        this.totalBalanceEuros = await this.calculateTotalBalanceEuros();

        // Affichez un message de réussite (vous pouvez le remplacer par votre propre mécanisme de notification)
        console.log(`Vente confirmée pour : ${transaction.currency_name}`);

        // Fermez automatiquement la boîte de dialogue après la confirmation
        this.sellDialog = false;

        // Définissez sellingInProgress sur false pour indiquer que la vente est terminée
        this.sellingInProgress = false;
      } catch (error) {
        console.log("Error during selling operation: ", error);
      }
    },

    cancelSellCrypto() {
      this.sellDialog = false;
    },

    isNegativeCapitalGain(transaction) {
      if (
        !isNaN(transaction.purchase_price) &&
        !isNaN(transaction.selling_price)
      ) {
        const purchasePrice = parseFloat(transaction.purchase_price);
        const sellingPrice = parseFloat(transaction.selling_price);
        const capitalGain =
          (sellingPrice - purchasePrice) * transaction.quantity;
        return capitalGain < 0;
      }
      return false;
    },

    isPositiveCapitalGain(transaction) {
      if (
        !isNaN(transaction.purchase_price) &&
        !isNaN(transaction.selling_price)
      ) {
        const purchasePrice = parseFloat(transaction.purchase_price);
        const sellingPrice = parseFloat(transaction.selling_price);
        const capitalGain =
          (sellingPrice - purchasePrice) * transaction.quantity;
        return capitalGain > 0;
      }
      return false;
    },

    formattedCapitalGain(transaction) {
      if (
        !isNaN(transaction.purchase_price) &&
        !isNaN(transaction.selling_price)
      ) {
        const purchasePrice = parseFloat(transaction.purchase_price);
        const sellingPrice = parseFloat(transaction.selling_price);
        const capitalGain =
          (sellingPrice - purchasePrice) * transaction.quantity;
        return new Intl.NumberFormat("fr-FR", {
          style: "currency",
          currency: "EUR",
        }).format(capitalGain);
      }
      return "";
    },
  },
  components: { SideBarNavClient },
};
</script>

<style>
.text-red {
  color: red;
}

.text-green {
  color: green;
}

.arrow-up {
  color: green;
  margin-left: 5px;
}

.arrow-down {
  color: red;
  margin-left: 5px;
}
</style>
