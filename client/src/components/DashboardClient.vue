<template>
  <!-- Vue application -->
  <v-app>
    <v-main>
      <v-container>
        <v-card>
          <!-- Card title displaying Client Information -->
          <v-card-title>Informations : {{ firstName }} {{ lastName }}</v-card-title>
          <v-card-text>
            <!-- Display user details -->
            <p><strong>Nom:</strong> {{ lastName }}</p>
            <p><strong>Prénom:</strong> {{ firstName }}</p>
            <p><strong>Email:</strong> {{ email }}</p>
            <p><strong>Status:</strong> {{ userStatus }}</p>
            <v-btn @click="openDialog">Modifier</v-btn>
          </v-card-text>
        </v-card>

        <!-- Dialog for editing user data -->
        <v-dialog v-model="dialog" max-width="500">
          <v-card>
            <v-card-title>Modifier les données</v-card-title>
            <v-card-text>
              <form @submit.prevent="saveUserData">
                <v-text-field v-model="lastName" label="Nom"></v-text-field>
                <v-text-field v-model="firstName" label="Prénom"></v-text-field>
                <v-text-field v-model="email" label="Email"></v-text-field>
                <v-text-field v-model="userStatus" label="Status"></v-text-field>
                <v-btn type="submit">Enregistrer</v-btn>
              </form>
            </v-card-text>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn @click="closeDialog">Annuler</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      lastName: "", // Initialize last name
      firstName: "", // Initialize first name
      email: "", // Initialize email
      userStatus: "", // Initialize user status
      dialog: false, // Dialog control variable
    };
  },
  mounted() {
    // Assume you have stored user data in a Vuex store or similar
    this.lastName = this.$store.state.userData.last_name; // Set last name from stored data
    this.firstName = this.$store.state.userData.first_name; // Set first name from stored data
    this.email = this.$store.state.userData.email; // Set email from stored data
    this.userStatus = this.$store.state.userData.status; // Set user status from stored data
  },
  methods: {
    openDialog() {
      this.dialog = true; // Open the dialog when the "Modifier" button is clicked
    },
    closeDialog() {
      this.dialog = false; // Close the dialog when the "Annuler" button is clicked
    },
    saveUserData() {
      // Create a data object to send to the server
      const userData = {
        last_name: this.lastName,
        first_name: this.firstName,
        email: this.email,
        status: this.userStatus,
      };

      const userId = this.$route.params.id; // Get ID from the URL

      // Perform an HTTP PUT request to update the user data using Axios
      axios
        .put(`/api/users/${userId}`, userData)
        .then((response) => {
          console.log(response.data.message);
          // Redirect to the users list upon success
          this.$router.push("/customers"); // Make sure the path is correct
        })
        .catch((error) => {
          console.error(error);
        });

      this.closeDialog(); // Close the dialog after saving
    },
  },
};
</script>
