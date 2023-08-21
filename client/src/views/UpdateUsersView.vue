<template>
    <div>
      <h1>Modifier l'Utilisateur</h1>
      <form @submit.prevent="updateUser">
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
          <label for="status">Statut</label>
          <select id="status" v-model="userData.status" required>
            <option value="client">Client</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <button type="submit">Enregistrer Modifications</button>
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
        status: ''
      }
    };
  },
  created() {
    this.fetchUser(); // Charge les données de l'utilisateur existant
  },
  methods: {
    fetchUser() {
  const userId = this.$route.params.id; // Obtient l'ID de l'URL
  axios.get(`/api/users/${userId}`)
    .then(response => {
      this.userData = response.data;
    })
    .catch(error => {
      console.error(error);
    });
},

    
    updateUser() {
  const userId = this.$route.params.id;
  axios.put(`/api/users/${userId}`, this.userData)
    .then(response => {
      console.log(response.data.message);
      // Redirige vers la liste des utilisateurs après succès
      this.$router.push('/customers'); // Assurez-vous que le chemin est correct
    })
    .catch(error => {
      console.error(error);
    });
}

    }
  };




  </script>
  
  <style>
  /* Vos styles CSS */
  </style>
  