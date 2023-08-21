<template>
  <v-app>
    <side-bar-nav></side-bar-nav>

    <v-app-bar app>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>Gestion des clients</v-toolbar-title>
    </v-app-bar>

    <v-main>
      <v-container>
        <div class="pa-2">
          <h1 class="headline">Liste des Utilisateurs</h1>
          <v-responsive>
            <router-link to="/add-user">
      <button>Ajouter Utilisateur</button>
    </router-link>
            <table class="table">
              <thead>
                <tr>
                  <th class="text-left id-column">ID</th>
                  <th class="text-left email-column">Email</th>
                  <th class="text-left">Statut</th>
                  <th class="text-left">Supprimer</th>
                  <th class="text-left">Modifier</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="user in users" :key="user.id">
                  <td class="text-left">{{ user.id }}</td>
                  <td class="text-left email-column">{{ user.email }}</td>
                  <td class="text-left">{{ user.status }}</td>
                  <td class="text-left">
                    <v-btn @click="deleteUser(user.id)" class="mt-2" color="error">Supprimer</v-btn>
                  </td>
                  <td class="text-left">
                    <v-btn class="mt-2" color="primary">Modifier</v-btn>
                  </td>
                </tr>
              </tbody>
            </table>
          </v-responsive>
        </div>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import SideBarNav from '@/components/SideBarNav.vue';
import axios from 'axios';

export default {
    data() {
        return {
            users: []
        };
    },
    mounted() {
        this.fetchUsers();
    },
    methods: {
        fetchUsers() {
            axios.get('/api/users')
                .then(response => {
                this.users = response.data;
            })
                .catch(error => {
                console.error(error);
            });
        },
        async deleteUser(id) {
    try {
        await axios.delete(`/api/users/${id}`);
        // Remove the user from the list only after successful deletion
        this.users = this.users.filter(user => user.id !== id);
    } catch (error) {
        console.error(error);
    }
},
    },

    components: { SideBarNav }
};
</script>

<style>
.table {
  width: 100%;
  margin-top: 16px;
  border-collapse: collapse;
}

.text-left {
  text-align: left;
  border: 1px solid #ddd;
  padding: 8px;
}

.email-column {
  width: auto;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.id-column {
  width: 5%; /* Adjust the width as needed */
}

.pa-2 {
  padding: 16px;
}
</style>
