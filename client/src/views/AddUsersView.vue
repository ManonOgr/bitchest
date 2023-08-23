<template>
  
    <v-container class="mt-10 add-box">
    <h1 class="text-h5">Ajouter un Utilisateur</h1>
    <v-form @submit.prevent="addUser" class="mt-10">
      <v-row>
        <v-col cols="12" md="6">
          <v-text-field v-model="userData.first_name" label="Prénom" required></v-text-field>
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field v-model="userData.last_name" label="Nom de Famille" required></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="6">
          <v-text-field v-model="userData.email" label="Email" type="email" required></v-text-field>
        </v-col>
        <v-col cols="12" md="6">
          <v-text-field v-model="userData.password" label="Mot de Passe" type="password" required></v-text-field>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12" md="6">
         
          <select id="status" v-model="userData.status" required>
            <option value="client">Client</option>
            <option value="admin">Admin</option>
          </select>
   
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="12">
          <v-btn @click="addUser" color="primary">Ajouter</v-btn>
        </v-col>
      </v-row>
    </v-form>
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
        status: 'client'
      }
    };
  },
  methods: {
    addUser() {
      axios.post('/api/users', this.userData)
        .then(response => {
          console.log(response.data.message);
          // Rediriger vers la page 'Customers' après succès
          this.$router.push('/customers'); 
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
</style>
