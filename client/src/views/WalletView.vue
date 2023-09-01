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
        <h2>Solde de mon compte en Euros: {{ formattedFakeAccountBalance }}</h2> <!-- Afficher la donnée fictive ici -->
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
                    Vendre
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
// Importez les composants et bibliothèques nécessaires ici...
import SideBarNavClient from "@/components/SideBarNavClient.vue";
import axios from "axios";

export default {
  data() {
    return {
      userTransactions: [],
      totalBalanceEuros: 0,
      fakeAccountBalance: 0,
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

    // Vérifiez si fakeAccountBalance est un nombre valide avant d'ajouter le selling_price
    if (!isNaN(this.fakeAccountBalance) && !isNaN(transaction.selling_price)) {
      this.fakeAccountBalance += parseFloat(transaction.selling_price);
    } else {
      console.log("Invalid value for fakeAccountBalance or selling_price.");
    }

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
