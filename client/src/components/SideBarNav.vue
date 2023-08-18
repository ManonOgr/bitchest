<template>
  <v-navigation-drawer app>
    <img
      src="../assets/bitchest_logo.png"
      alt="logo"
      style="max-width: 100%; height: auto"
    />
    <v-list>
      <v-list-item link to="/dashboardadmin">
        <v-list-item-icon>
          <v-icon>mdi-home</v-icon>
        </v-list-item-icon>
        <v-list-item-title>Données personnelles</v-list-item-title>
      </v-list-item>
      <v-list-item link to="/customers">
        <v-list-item-icon>
          <v-icon>mdi-account</v-icon>
        </v-list-item-icon>
        <v-list-item-title>Gestion des clients</v-list-item-title>
      </v-list-item>
      <v-list-item link to="/ratesadmin">
        <v-list-item-icon>
          <v-icon>mdi-settings</v-icon>
        </v-list-item-icon>
        <v-list-item-title>Cours des cryptos</v-list-item-title>
      </v-list-item>
    </v-list>
    <div class="text-center">
      <v-btn @click="logout" class="mt-4" variant="tonal">Déconnexion</v-btn>
    </div>
  </v-navigation-drawer>
</template>


<script setup>
import axios from "axios";
import router from "@/routers";

function logout() {
  try {
    axios
      .post(
        "http://127.0.0.1:8000/api/logout",
        {},
        {
          // withCredentials: true,
          headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
            "Access-Control-Allow-Origin": "*",
          },
        }
      )
      .then((res) => {
        console.log(res);
        if (res.status === 204) {
          router.push("/");
        }
      });

    //unable to connect
  } catch (error) {
    console.log(error);
  }
}
</script>
