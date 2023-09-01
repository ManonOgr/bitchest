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
        <h1>List of My Cryptos</h1>
        <h2>Total Balance in Euros: {{ formattedTotalBalanceEuros }}</h2>
        <h2>My Account Balance: {{ formattedUserBalance }}</h2>
        <v-responsive>
          <v-table height="300px">
            <thead>
              <tr>
                <th class="text-left id-column">ID Crypto</th>
                <th class="text-left">Nom de la crypto</th>
                <th class="text-left">Quantité</th>
                <th class="text-left">Date d'achat</th>
                <th class="text-left">Prix d'achat</th>
                <th class="text-left">Prix de vente</th>
                <th class="text-left">Actions</th> <!-- Ajout de la colonne d'actions -->
              </tr>
            </thead>
            <tbody>
<!-- Loop through user's transactions and display them -->
              <tr v-for="transaction in userTransactions" :key="transaction.id">
                <td class="text-left">{{ transaction.currency_id }}</td>
                <td class="text-left">{{ transaction.currency_name }}</td>
                <td class="text-left">{{ transaction.quantity }}</td>
                <td class="text-left">{{ transaction.purchase_date }}</td>
                <td class="text-left">{{ transaction.purchase_price }}</td>
<td class="text-left">{{ transaction.selling_price }}</td>
                <td class="text-left">
                  <v-btn color="primary" @click="sellCrypto(transaction)">
                    Sell
                  </v-btn>
                </td>
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
      totalBalanceEuros: 0, // Initialize the total balance in euros
    };
  },
  computed: {
    formattedTotalBalanceEuros() {
      return new Intl.NumberFormat("fr-FR", {
        style: "currency",
        currency: "EUR",
      }).format(this.totalBalanceEuros);
    },
    formattedUserBalance() {
      if (this.$store.state.userData) {
        return new Intl.NumberFormat("en-US", {
          style: "currency",
          currency: "EUR",
        }).format(this.$store.state.userData.euro_balance);
      }
      return '';
    },
  },
  async created() {
    try {
// Check if user data exists in the store
      if (this.$store.state.userData) {
        const userId = this.$store.state.userData.id;
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
    async calculateTotalBalanceEuros() {
      try {
        let totalBalanceEuros = 0;
        for (const transaction of this.userTransactions) {
          const transactionTotal =
            transaction.quantity * transaction.purchase_price;
          totalBalanceEuros += transactionTotal;
        }
        return totalBalanceEuros;
      } catch (error) {
        console.log("Error calculating total balance: ", error);
        return 0;
      }
    },
    async sellCrypto(transaction) {
      try {
        // Supprimez la crypto du portefeuille de l'utilisateur
        const index = this.userTransactions.findIndex((t) => t.id === transaction.id);
        if (index !== -1) {
          this.userTransactions.splice(index, 1);
        }

        // Déduisez le purchase_price de la valeur de formattedTotalBalanceEuros
    this.totalBalanceEuros -= transaction.quantity * transaction.purchase_price;

          // Mettez à jour la valeur de formattedTotalBalanceEuros
    // Vous pouvez rappeler this.calculateTotalBalanceEuros() si nécessaire.

        // Recalculez le solde total en euros
        this.totalBalanceEuros = await this.calculateTotalBalanceEuros();

        // Affichez un message de réussite (vous pouvez le remplacer par votre propre mécanisme de notification)
        console.log(`Sold cryptocurrency: ${transaction.currency_name}`);
      } catch (error) {
        console.log("Error during selling operation: ", error);
      }
    },
  },
  components: { SideBarNavClient },
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
