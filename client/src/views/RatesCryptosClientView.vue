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
              <th class="text-left">Name</th>
              <th class="text-left">Cours</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="crypto in cryptos" :key="crypto">
              <td>{{ crypto }}</td>
              <td>iuyt</td>
            </tr>
          </tbody>
        </v-table>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import axios from "axios";
import SidebarNavClient from "@/components/SideBarNavClient.vue";

export default {
  components: {
    SidebarNavClient,
  },
  data() {
    return {
      drawer: false,
      cryptos: [],
    };
  },
  mounted() {
    this.fetchCryptos();
  },
  methods: {
    async fetchCryptos() {
      try {
        const response = await axios.get("/api/currencies/names");
        this.cryptos = response.data;
        console.log(response.data);
        console.log(this.cryptos);
      } catch (error) {
        console.error(
          "Erreur lors de la récupération des cryptomonnaies :",
          error
        );
      }
    },
  },
};
</script>
