<template>
  <div>
    <!-- Vue navigation drawer -->
    <v-navigation-drawer app>
      <!-- Logo image -->
      <img
        src="../assets/bitchest_logo.png"
        alt="logo"
        style="max-width: 100%; height: auto"
      />
      <!-- List of navigation items -->
      <v-list>
        <!-- Navigate to admin dashboard -->
        <v-list-item link to="/dashboardclient">
          <v-list-item-icon>
            <v-icon>mdi-home</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Données personnelles</v-list-item-title>
        </v-list-item>
        <!-- Navigate to customers management -->
        <v-list-item link to="/wallet">
          <v-list-item-icon>
            <v-icon>mdi-account</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Portefeuille</v-list-item-title>
        </v-list-item>
        <!-- Navigate to crypto rates configuration -->
        <v-list-item link to="/ratesclient">
          <v-list-item-icon>
            <v-icon>mdi-settings</v-icon>
          </v-list-item-icon>
          <v-list-item-title>Cours des Cryptos</v-list-item-title>
        </v-list-item>
      </v-list>
      <!-- Logout button -->
      <div class="text-center">
        <v-btn @click="showLogoutDialog" class="mt-4" variant="tonal"
          >Déconnexion</v-btn
        >
      </div>
    </v-navigation-drawer>

    <!-- Logout Confirmation Dialog -->
    <v-dialog v-model="logoutDialog" max-width="400">
      <v-card>
        <v-card-title class="headline">Confirmation</v-card-title>
        <v-card-text> Êtes-vous sûr de vouloir vous déconnecter ? </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" text @click="logoutDialog = false"
            >Annuler</v-btn
          >
          <v-btn color="blue darken-1" text @click="logout">Déconnexion</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios"; // Import Axios library for HTTP requests
import router from "@/routers"; // Import Vue Router instance for navigation

// State for the logout confirmation dialog
const logoutDialog = ref(false);

// Function to show the logout confirmation dialog
function showLogoutDialog() {
  logoutDialog.value = true;
}

// Function to handle logout
function logout() {
  try {
    // Send a POST request to logout endpoint
    axios
      .get("http://127.0.0.1:8000/api/logout", {
        // Define request headers
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          // "Access-Control-Allow-Origin": "*",
        },
        withCredentials: true,
      })
      .then((res) => {
        console.log(res);
        // If logout is successful, navigate to the home page
        if (res.status === 200) {
          router.push("/");
        }
      });

    // Handle errors during the logout process
  } catch (error) {
    console.log(error);
  } finally {
    // Close the logout confirmation dialog
    logoutDialog.value = false;
  }
}
</script>
