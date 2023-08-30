<template>
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
      <!-- Navigate to client dashboard -->
      <v-list-item link to="/dashboardclient">
        <v-list-item-icon>
          <v-icon>mdi-home</v-icon>
        </v-list-item-icon>
        <v-list-item-title>Données personnelles</v-list-item-title>
      </v-list-item>
      <!-- Navigate to wallet section -->
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
        <v-list-item-title>Cours des cryptos</v-list-item-title>
      </v-list-item>
    </v-list>
    <!-- Logout button -->
    <div class="text-center">
      <v-btn @click="logout" class="mt-4" variant="tonal">Déconnexion</v-btn>
    </div>
  </v-navigation-drawer>
</template>

<script setup>
import axios from "axios"; // Import Axios library for HTTP requests
import router from "@/routers"; // Import Vue Router instance for navigation

// Function to handle logout
function logout() {
  try {
    // Send a POST request to logout endpoint
    axios
      .post(
        "http://127.0.0.1:8000/api/logout",
        {},
        {
          // Define request headers
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            "Access-Control-Allow-Origin": "*",
          },
        }
      )
      .then((res) => {
        console.log(res);
        // If logout is successful, navigate to the home page
        if (res.status === 204) {
          router.push("/");
        }
      });

    // Handle errors during the logout process
  } catch (error) {
    console.log(error);
  }
}
</script>
