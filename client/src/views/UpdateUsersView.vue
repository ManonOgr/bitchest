<template>
  <v-container class="mt-10 add-box">
    <h1 class="headline">Modifier l'Utilisateur</h1>
    <v-form @submit.prevent="updateUser">
      <v-text-field
        v-model="userData.first_name"
        label="Prénom"
        required
      ></v-text-field>
      <v-text-field
        v-model="userData.last_name"
        label="Nom de Famille"
        required
      ></v-text-field>
      <v-text-field
        v-model="userData.email"
        label="Email"
        type="email"
        required
      ></v-text-field>
      <div>
        <select id="status" v-model="userData.status" required>
          <option value="" disabled selected>Choisissez un statut</option>
          <option value="client">Client</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <v-btn type="submit" depressed>Enregistrer</v-btn>
    </v-form>
  </v-container>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      userData: {
        first_name: "",
        last_name: "",
        email: "",
        status: "",
      },
    };
  },
  created() {
    this.fetchUser(); // Charge les données de l'utilisateur existant
  },
  methods: {
    fetchUser() {
      const userId = this.$route.params.id; // Obtient l'ID de l'URL
      axios
        .get(`/api/users/${userId}`)
        .then((response) => {
          this.userData = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    updateUser() {
      const userId = this.$route.params.id;
      axios
        .put(`/api/users/${userId}`, this.userData)
        .then((response) => {
          console.log(response.data.message);
          // Redirige vers la liste des utilisateurs après succès
          this.$router.push("/customers"); // Assurez-vous que le chemin est correct
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
