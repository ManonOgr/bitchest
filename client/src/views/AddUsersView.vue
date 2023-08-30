<template>
  <!-- Container for adding a user -->
  <v-container class="mt-10 add-box">
    <h1 class="text-h5">Ajouter un utilisateur</h1> <!-- Heading -->
    <v-form @submit.prevent="addUser" class="mt-10"> <!-- Form to submit user data -->
      <v-row>
        <v-col cols="12" md="6">
          <v-text-field v-model="userData.first_name" label="Prénom" required></v-text-field> <!-- Input field for first name -->
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field v-model="userData.last_name" label="Nom" required></v-text-field> <!-- Input field for last name -->
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="6">
          <v-text-field v-model="userData.email" label="Email" type="email" required></v-text-field> <!-- Input field for email -->
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field v-model="userData.password" label="Password" type="password" required></v-text-field> <!-- Input field for password -->
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="6">
          <select id="status" v-model="userData.status" required> <!-- Dropdown for selecting status -->
            <option value="" disabled selected>Selectionne un status</option>
            <option value="client">Client</option>
            <option value="admin">Admin</option>
          </select>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12">
          <v-btn @click="addUser" depressed>Ajouter</v-btn> <!-- Button to add user -->
        </v-col>
      </v-row>
    </v-form>
  </v-container>
</template>

<script>
import axios from 'axios'; // Importing axios for making API requests

export default {
  data() {
    return {
      userData: {
        first_name: '',
        last_name: '',
        email: '',
        password: '',
        status: ''
      }
    };
  },
  methods: {
    addUser() { // Method to add a user
      axios.post('/api/users', this.userData) // Sending user data to API endpoint
        .then(response => {
          console.log(response.data.message); // Logging success message from API response
          // Redirect to the 'Customers' page on success
          this.$router.push('/customers'); 
        })
        .catch(error => {
          console.error(error); // Logging any errors that occur during the API request
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

/* style for the select dropdown */
#status {
  font-size: 16px;
  padding: 10px;
  border-bottom: 1px solid rgb(62, 61, 61);
  color: #333;
  background: rgba(36, 36, 36, 0.1);;
  width: 100%;
  margin: 8px 0;
}
</style>
