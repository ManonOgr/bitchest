<template>
  <!-- Vue app container -->
  <v-app>
    <!-- Sidebar navigation component -->
    <side-bar-nav></side-bar-nav>

    <!-- Main content area -->
    <v-main>
      <v-container>
        <div class="pa-2">
          <h1 class="headline">Liste des utilisateurs</h1>
          <!-- List of users -->
          <v-responsive>
            <v-btn class="add" color="success" @click="openAddUserDialog"
              >Ajouter</v-btn
            >
            <!-- Add -->
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
                    >
                      Supprimer
                      <!-- Delete -->
                    </v-btn>
                  </td>
                  <td class="text-left">
                    <v-btn
                      class="btn"
                      color="purple"
                      @click="openEditDialog(user)"
                    >
                      Modifier
                      <!-- Edit -->
                    </v-btn>
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
        <!-- Confirmation -->
        <v-card-text>
          Êtes-vous sûr de vouloir supprimer cet utilisateur ?
          <!-- Are you sure you want to delete this user? -->
        </v-card-text>
        <v-card-actions>
          <v-btn color="error" @click="deleteUserConfirmed">Supprimer</v-btn>
          <!-- Delete -->
          <v-btn text @click="deleteDialog = false">Annuler</v-btn>
          <!-- Cancel -->
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Composant de dialogue pour la modification de l'utilisateur -->
    <v-dialog v-model="editDialog" max-width="600">
      <v-card>
        <v-card-title class="headline">Modifier un utilisateur</v-card-title>
        <!-- Edit User -->
        <v-card-text>
          <v-form @submit.prevent="showEditConfirmationDialog">
            <!-- Form fields for user data -->
            <v-text-field
              v-model="userData.first_name"
              label="Prénom"
              required
            ></v-text-field>
            <!-- First Name -->
            <v-text-field
              v-model="userData.last_name"
              label="Nom"
              required
            ></v-text-field>
            <!-- Last Name -->
            <v-text-field
              v-model="userData.email"
              label="Email"
              type="email"
              required
            ></v-text-field>
            <!-- Email -->
            <div>
              <!-- Dropdown for user status selection -->
              <select id="status" v-model="userData.status" required>
                <option value="" disabled selected>
                  Selectionne un status
                </option>
                <option value="client">Client</option>
                <option value="admin">Admin</option>
              </select>
            </div>
            <!-- Button to confirm the update -->
            <v-btn @click="showEditConfirmationDialog" depressed color="purple"
              >Modifier</v-btn
            >
          </v-form>
        </v-card-text>
        <v-card-actions>
          <!-- Button to close the edit popup -->
          <v-btn @click="closeEditDialog" text>Annuler</v-btn>
          <!-- Cancel -->
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Popup for confirmation of edit -->
    <v-dialog v-model="confirmEditDialog" max-width="400">
      <v-card>
        <v-card-title class="headline"
          >Confirmation de modification</v-card-title
        >
        <v-card-text>
          Êtes-vous sûr de vouloir appliquer ces modifications ?
          <!-- Are you sure you want to apply these changes? -->
        </v-card-text>
        <v-card-actions>
          <v-btn color="purple" @click="updateUser">Oui</v-btn>
          <!-- Yes -->
          <v-btn text @click="confirmEditDialog = false">Non</v-btn>
          <!-- No -->
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Composant de dialogue pour l'ajout d'utilisateur -->
    <v-dialog v-model="addUserDialog" max-width="600">
      <v-card>
        <v-card-title class="headline">Ajouter un utilisateur</v-card-title>
        <!-- Add User -->
        <v-card-text>
          <v-form @submit.prevent="addUser">
            <!-- Champs de formulaire pour les données de l'utilisateur -->
            <v-text-field
              v-model="userData.first_name"
              label="Prénom"
              required
            ></v-text-field>
            <!-- First Name -->
            <v-text-field
              v-model="userData.last_name"
              label="Nom"
              required
            ></v-text-field>
            <!-- Last Name -->
            <v-text-field
              v-model="userData.email"
              label="Email"
              type="email"
              required
            ></v-text-field>
            <!-- Email -->
            <v-text-field
              v-model="userData.password"
              label="Mot de passe"
              type="password"
              required
            ></v-text-field>
            <!-- Password -->
            <div>
              <!-- Menu déroulant pour la sélection du statut de l'utilisateur -->
              <select id="status" v-model="userData.status" required>
                <option value="" disabled selected>
                  Selectionne un status
                </option>
                <option value="client">Client</option>
                <option value="admin">Admin</option>
              </select>
            </div>
            <!-- Bouton pour confirmer l'ajout -->
            <v-btn @click="addUser" depressed color="success">Ajouter</v-btn>
            <!-- Add -->
          </v-form>
        </v-card-text>
        <v-card-actions>
          <!-- Bouton pour fermer la boîte de dialogue d'ajout d'utilisateur -->
          <v-btn @click="closeAddUserDialog" text>Annuler</v-btn>
          <!-- Cancel -->
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
      editDialog: false,
      userToEdit: null,
      addUserDialog: false,
      userData: {
        id: null,
        first_name: "",
        last_name: "",
        email: "",
        status: "",
        password: "",
      },
      confirmEditDialog: false,
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
    openEditDialog(user) {
      this.userToEdit = user;
      this.userData = { ...user };
      this.userData.id = user.id;
      this.editDialog = true;
    },
    closeEditDialog() {
      this.editDialog = false;
    },
    showEditConfirmationDialog() {
      this.confirmEditDialog = true;
    },
    updateUser() {
      const userId = this.userData.id;
      axios
        .put(`/api/users/${userId}`, this.userData)
        .then(() => {
          const updatedUserIndex = this.users.findIndex(
            (user) => user.id === userId
          );
          if (updatedUserIndex !== -1) {
            this.users[updatedUserIndex] = { ...this.userData };
          }

          this.editDialog = false;
          this.confirmEditDialog = false;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    openAddUserDialog() {
      this.addUserDialog = true;
      // Reset input fields for adding a user
      this.userData = {
        id: null,
        first_name: "",
        last_name: "",
        email: "",
        status: "",
        password: "",
      };
    },
    closeAddUserDialog() {
      this.addUserDialog = false;
    },
    addUser() {
      axios
        .post("/api/users", this.userData)
        .then((response) => {
          // Add the new user to the list of users
          this.users.push({ ...this.userData, id: response.data.id });

          this.addUserDialog = false;

          // Reload the table after adding a user
          this.fetchUsers();
        })
        .catch((error) => {
          console.error(error);
        });
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

.add {
  margin-bottom: 15px;
}

.btn {
  text-align: center;
  margin-bottom: 0px;
  margin-top: 0px;
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
