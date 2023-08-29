<template>
  <v-app>
    <v-main>
      <v-container>
        <v-card>
          <v-card-title>Informations Admin : {{ prenom }} {{ nom }}</v-card-title>
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
export default {
  data() {
    return {
      nom: '',
      prenom: '',
      email: '',
      status:''
    };
  },
  mounted() {
    // Assume you have stored user data in a Vuex store or similar
    this.nom = this.$store.state.userData.last_name;
    this.prenom = this.$store.state.userData.first_name;
    this.email = this.$store.state.userData.email;
    this.status = this.$store.state.userData.status;
  },
};
</script>

<style>
/* Vos styles ici */
</style>
