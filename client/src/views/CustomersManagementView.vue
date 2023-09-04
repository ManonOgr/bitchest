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
            <v-table height="570px">
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
                      @click="openDeleteDialog(user)"
                      class="btn"
                      color="error"
                      >Supprimer</v-btn
                    >
                  </td>
                  <td class="text-left">
                    <v-btn
                      class="btn"
                      color="purple"
                      @click="
                        $router.push({
                          name: 'updateuser',
                          params: { id: user.id },
                        })
                      "
                      >Modifier</v-btn
                    >
                  </td>
                </tr>
              </tbody>
            </v-table>
          </v-responsive>
        </div>
      </v-container>
    </v-main>

    <!-- Dialog component for delete confirmation -->
    <v-dialog v-model="deleteDialog" max-width="400">
      <v-card>
        <v-card-title class="headline">Confirmation</v-card-title>
        <v-card-text>
          Êtes-vous sûr de vouloir supprimer cet utilisateur ?
        </v-card-text>
        <v-card-actions>
          <v-btn color="error" @click="deleteUserConfirmed">Supprimer</v-btn>
          <v-btn text @click="deleteDialog = false">Annuler</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-app>
</template>

<script>
import SideBarNav from "@/components/SideBarNav.vue";
import axios from "axios";

export default {
  data() {
    return {
      users: [],
      deleteDialog: false,
      userToDelete: null,
    };
  },
  mounted() {
    this.fetchUsers();
  },
  methods: {
    fetchUsers() {
      axios
        .get("/api/users")
        .then((response) => {
          this.users = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    openDeleteDialog(user) {
      this.userToDelete = user;
      this.deleteDialog = true;
    },
    async deleteUserConfirmed() {
      if (this.userToDelete) {
        const id = this.userToDelete.id;
        try {
          await axios.delete(`/api/users/${id}`);
          this.users = this.users.filter((user) => user.id !== id);
          this.deleteDialog = false;
        } catch (error) {
          console.error(error);
        }
      }
    },
  },
  components: { SideBarNav },
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
  width: 5%;
}

.pa-2 {
  padding: 16px;
}

.btn {
  text-align: center;
  margin-bottom: 0px;
  margin-top: 0px;
}
</style>
