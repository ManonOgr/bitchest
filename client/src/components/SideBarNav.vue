<template>
  <div>
    <!-- Vue navigation drawer -->
    <v-navigation-drawer app :value="drawer" :mini-variant.sync="mini">
      <!-- Logo image -->
      <img
        src="../assets/bitchest_logo.png"
        alt="logo"
        style="max-width: 100%; height: auto"
      />
      <!-- List of navigation items -->
      <v-list>
        <!-- Navigate to admin dashboard -->
        <v-list-item link to="/dashboardadmin">
          <v-list-item-icon>
            <v-icon>mdi-home</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Données personnelles</v-list-item-title>
        </v-list-item>
        <!-- Navigate to customers management -->
        <v-list-item link to="/customers">
          <v-list-item-icon>
            <v-icon>mdi-account</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Gestion des clients</v-list-item-title>
        </v-list-item>
        <!-- Navigate to crypto rates configuration -->
        <v-list-item link to="/ratesadmin">
          <v-list-item-icon>
            <v-icon>mdi-settings</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Cours des Cryptos</v-list-item-title>
        </v-list-item>
      </v-list>
      <!-- Logout button -->
      <div class="text-center">
        <v-btn @click="showLogoutDialog" class="mt-4" variant="tonal">Déconnexion</v-btn>
      </div>
    </v-navigation-drawer>
    <!-- App bar for the application -->
    <v-app-bar app>
      <!-- Navigation icon for opening/closing sidebar -->
      <v-app-bar-nav-icon @click.stop="toggleSidebar"> </v-app-bar-nav-icon>
      <!-- Title for the app bar -->
      <!-- Use pageTitle in v-toolbar-title -->
      <v-toolbar-title>{{ pageTitle }}</v-toolbar-title>
    </v-app-bar>
    <!-- Logout Confirmation Dialog -->
    <v-dialog v-model="logoutDialog" max-width="400">
      <v-card>
        <v-card-title class="headline">Confirmation</v-card-title>
        <v-card-text> Êtes-vous sûr de vouloir vous déconnecter ? </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="logoutDialog = false">Annuler</v-btn>
          <v-btn color="blue darken-1" text @click="logout">Déconnexion</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import axios from "axios";
import router from "@/routers";
import { useRoute } from "vue-router";

const logoutDialog = ref(false);
const sessionToken = localStorage.getItem("user");
const route = useRoute();

function showLogoutDialog() {
  logoutDialog.value = true;
}

function logout() {
  try {
    axios
      .get("http://127.0.0.1:8000/api/logout", {
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          Authorization: `Bearer ${sessionToken}`,
        },
        withCredentials: true,
      })
      .then((res) => {
        console.log(res);
        if (res.status === 200) {
          router.push("/");
        }
      });
  } catch (error) {
    console.log(error);
  } finally {
    logoutDialog.value = false;
  }
}

// Update the pageTitle computed property to display titles based on the route
const pageTitle = computed(() => {
  switch (route.path) {
    case "/dashboardadmin":
      return "Données personnelles";
    case "/customers":
      return "Gestion des clients";
    case "/ratesadmin":
      return "Cours des Cryptos";
    default:
      return "Unknown Page";
  }
});
</script>
