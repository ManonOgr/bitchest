<template>
  <!-- Container for the user update form -->
  <v-container class="mt-10 add-box">
    <h1 class="headline">Modifier un utilisateur</h1>
    <v-form @submit.prevent="openUpdateUserDialog">
      <!-- Input fields for user data -->
      <v-text-field
        v-model="userData.first_name"
        label="Prénom"
        required
      ></v-text-field>
      <v-text-field
        v-model="userData.last_name"
        label="Nom"
        required
      ></v-text-field>
      <v-text-field
        v-model="userData.email"
        label="Email"
        type="email"
        required
      ></v-text-field>
      <div>
        <!-- Dropdown for user status selection -->
        <select id="status" v-model="userData.status" required>
          <option value="" disabled selected>Selectionne un status</option>
          <option value="client">Client</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <!-- Button to open the confirmation dialog -->
      <v-btn @click="openUpdateUserDialog" depressed color="purple">Modifier</v-btn>
    </v-form>

    <!-- Dialog component for confirmation -->
    <v-dialog v-model="confirmationDialog" max-width="400">
      <v-card>
        <v-card-title class="headline">Confirmation</v-card-title>
        <v-card-text>
          Êtes-vous sûr de vouloir modifier cet utilisateur ?
        </v-card-text>
        <v-card-actions>
          <v-btn color="purple" @click="updateUser">Confirmer</v-btn>
          <v-btn text @click="closeConfirmationDialog">Annuler</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import axios from "axios"; // Importing axios for making API requests

export default {
  data() {
    return {
      userData: {
        first_name: "",
        last_name: "",
        email: "",
        status: "",
      },
      confirmationDialog: false, // Added confirmationDialog state
    };
  },
  created() {
    this.fetchUser(); // Load existing user data
  },
  methods: {
    fetchUser() {
      const userId = this.$route.params.id; // Get ID from the URL
      axios
        .get(`/api/users/${userId}`) // Fetch user data based on ID
        .then((response) => {
          this.userData = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    openUpdateUserDialog() {
      this.confirmationDialog = true; // Open the confirmation dialog
    },
    closeConfirmationDialog() {
      this.confirmationDialog = false; // Close the confirmation dialog
    },
    updateUser() {
      const userId = this.$route.params.id; // Get ID from the URL
      axios
        .put(`/api/users/${userId}`, this.userData) // Update user data using PUT request
        .then((response) => {
          console.log(response.data.message);
          // Redirect to the users list upon success
          this.$router.push("/customers"); // Make sure the path is correct
          this.confirmationDialog = false; // Close the confirmation dialog on success
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>

<style scoped>
.headline {
  font-size: 24px;
  margin-bottom: 20px;
}

.add-box {
  background: rgba(104, 102, 102, 0.5);
  box-sizing: border-box;
  box-shadow: 0 15px 25px rgba(0, 0, 0, 0.6);
  border-radius: 10px;
}

#status {
  font-size: 16px;
  padding: 10px;
  border-bottom: 1px solid rgb(62, 61, 61);
  color: #333;
  background: rgba(36, 36, 36, 0.1);
  width: 100%;
  margin: 8px 0;
}
</style>
