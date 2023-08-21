<template>
    <div>
      <h1>Ajouter un Utilisateur</h1>
      <form @submit.prevent="addUser">
        <div>
          <label for="first_name">Prénom</label>
          <input type="text" id="first_name" v-model="userData.first_name" required>
        </div>
        <div>
          <label for="last_name">Nom de Famille</label>
          <input type="text" id="last_name" v-model="userData.last_name" required>
        </div>
        <div>
          <label for="email">Email</label>
          <input type="email" id="email" v-model="userData.email" required>
        </div>
        <div>
          <label for="password">Mot de Passe</label>
          <input type="password" id="password" v-model="userData.password" required>
        </div>
        <div>
          <label for="status">Statut</label>
          <select id="status" v-model="userData.status" required>
            <option value="client">Client</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <v-btn @click="addUser" color="primary">Ajouter Utilisateur</v-btn>

      </form>
    </div>
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
  /* Ajoutez vos styles CSS ici */
  </style>
  