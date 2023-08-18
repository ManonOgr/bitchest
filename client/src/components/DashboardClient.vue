<template>
  <v-app>
    <v-main>
      <v-container>
        <v-card>
          <v-card-title>Informations Client</v-card-title>
          <v-card-text>
            <p><strong>Nom :</strong> {{ nom }}</p>
            <p><strong>Prénom :</strong> {{ prenom }}</p>
            <p><strong>E-mail :</strong> {{ email }}</p>
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
      nom: 'Quentin',
      prenom: 'Leroy',
      email: 'ekrrfrefe@example.com'
    };
  }
};
</script>

<style>
</style>
