<template>
  <!-- Vue app container -->
  <v-app>
    <!-- Sidebar navigation component -->
    <side-bar-nav></side-bar-nav>

    <!-- App bar for the application -->
    <v-app-bar app>
      <!-- Navigation icon for opening/closing sidebar -->
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <!-- Title for the app bar -->
      <v-toolbar-title>Gestion Client</v-toolbar-title>
    </v-app-bar>

    <!-- Main content area -->
    <v-main>
      <v-container>
        <div class="pa-2">
          <h1 class="headline">Liste des utilisateurs</h1>
          <v-responsive>
            <router-link to="/add-user">
              <v-btn color="success">Ajouter</v-btn>
            </router-link>
            <v-table height="300px">
              <thead>
                <tr>
                  <th class="text-left id-column">ID</th>
                  <th class="text-left">Prénom</th>
                  <th class="text-left">Nom</th>
                  <th class="text-left email-column">Email</th>
                  <th class="text-left">Status</th>
                  <th class="text-left">Supprimer</th>
                  <th class="text-left">Modifier</th>
                </tr>
              </thead>
              <tbody>
                <!-- Loop through users and display their data -->
                <tr v-for="user in users" :key="user.id">
                  <td class="text-left">{{ user.id }}</td>
                  <td class="text-left">{{ user.first_name }}</td>
                  <td class="text-left">{{ user.last_name }}</td>
                  <td class="text-left email-column">{{ user.email }}</td>
                  <td class="text-left">{{ user.status }}</td>
                  <td class="text-left">
                    <v-btn
                      @click="deleteUser(user.id)"
                      class="mt-2"
                      color="error"
                    >Supprimer</v-btn>
                  </td>
                  <td class="text-left">
                    <v-btn
                      color="purple"
                      @click="
                        $router.push({
                          name: 'updateuser',
                          params: { id: user.id },
                        })
                      "
                    >Modifier</v-btn>
                  </td>
                </tr>
              </tbody>
            </v-table>
          </v-responsive>
        </div>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import SideBarNav from "@/components/SideBarNav.vue"; // Importing the SidebarNav component
import axios from "axios"; // Importing axios for making API requests

export default {
  data() {
    return {
      users: [], // Array to hold user data
    };
  },
  mounted() {
    this.fetchUsers(); // Fetch users data when the component is mounted
  },
  methods: {
    fetchUsers() {
      axios
        .get("/api/users") // Fetching users data from API endpoint
        .then((response) => {
          this.users = response.data; // Assigning fetched data to the users array
        })
        .catch((error) => {
          console.error(error);
        });
    },
    async deleteUser(id) {
      try {
        await axios.delete(`/api/users/${id}`); // Sending a delete request to API endpoint
        // Remove the user from the list after successful deletion
        this.users = this.users.filter((user) => user.id !== id);
      } catch (error) {
        console.error(error);
      }
    },
  },

  components: { SideBarNav }, // Registering the SidebarNav component
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
