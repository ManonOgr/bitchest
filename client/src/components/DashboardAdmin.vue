<template>
  <v-app>
    <v-main>
      <v-container>
        <v-card>
          <v-card-title>Informations Admin</v-card-title>
          <v-card-text>
            <p><strong>Nom :</strong> {{ nom }}</p>
            <p><strong>Prénom :</strong> {{ prenom }}</p>
            <p><strong>E-mail :</strong> {{ email }}</p>
            <p><strong>Status :</strong> {{ status }}</p>
          </v-card-text>
        </v-card>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      nom: '',
      prenom: '',
      email: ''
    };
  },
  mounted() {
    try {
      axios
        .post(
          "http://127.0.0.1:8000/api/login",
          {
            email: 'admin@admin.com',
              password: 'Admin',
          },
          {
            headers: {
              Accept: "application/json",
              "Content-Type": "application/json",
              "Access-Control-Allow-Origin": "*",
            },
          }
        )
        .then((res) => {
          const userData = res.data.user; // Récupérer les données utilisateur depuis la réponse
          
          this.nom = userData.last_name;
          this.prenom = userData.first_name;
          this.email = userData.email;
          this.status = userData.status;
        });

      // En cas d'erreur de connexion
    } catch (error) {
      console.log(error);
    }
  },
};
</script>

<style>
/* Vos styles ici */
</style>
