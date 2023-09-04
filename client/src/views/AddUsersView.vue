<template>
  <!-- Container for adding a user -->
  <v-container class="mt-10 add-box pa-4">
    <h1 class="text-h5">Ajouter un utilisateur</h1>
    <v-form @submit.prevent="openAddUserDialog" class="mt-4">
      <v-row>
        <v-col cols="12" md="6">
          <v-text-field v-model="userData.first_name" label="Prénom" required></v-text-field>
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field v-model="userData.last_name" label="Nom" required></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="6">
          <v-text-field v-model="userData.email" label="Email" type="email" required></v-text-field>
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field v-model="userData.password" label="Password" type="password" required></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="6">
          <v-select
            v-model="userData.status"
            :items="['client', 'admin']"
            label="Status"
            required
          ></v-select>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12">
          <v-btn @click="openAddUserDialog" depressed color="success">Ajouter</v-btn>
        </v-col>
      </v-row>
    </v-form>

    <!-- Dialog component for confirmation -->
    <v-dialog v-model="confirmationDialog" max-width="400">
      <v-card>
        <v-card-title class="headline">Confirmation</v-card-title>
        <v-card-text>
          Êtes-vous sûr de vouloir ajouter cet utilisateur ?
        </v-card-text>
        <v-card-actions>
          <v-btn color="success" @click="addUser">Confirmer</v-btn>
          <v-btn text @click="closeConfirmationDialog">Annuler</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      userData: {
        first_name: '',
        last_name: '',
        email: '',
        password: '',
        status: ''
      },
      confirmationDialog: false // Added confirmationDialog state
    };
  },
  methods: {
    openAddUserDialog() {
      this.confirmationDialog = true; // Open the confirmation dialog
    },
    closeConfirmationDialog() {
      this.confirmationDialog = false; // Close the confirmation dialog
    },
    addUser() {
      axios.post('/api/users', this.userData)
        .then(response => {
          console.log(response.data.message);
          this.$router.push('/customers');
          this.confirmationDialog = false; // Close the confirmation dialog on success
        })
        .catch(error => {
          console.error(error);
        });
    }
  }
};
</script>

<style>
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
