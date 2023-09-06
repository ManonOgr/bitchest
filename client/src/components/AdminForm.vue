<template>
  <!-- Login form template -->
  <div class="login-box">
    <img
      src="../assets/bitchest_logo.png"
      alt="logo"
      style="max-width: 100%; height: auto"
    />
    <form @submit.prevent="formSubmit">
      <!-- Email input -->
      <div class="user-box">
        <input
          type="email"
          name="email"
          required
          placeholder="email"
          v-model="form.email"
        />
        <label></label>
      </div>
      <!-- Password input -->
      <div class="user-box">
        <input
          type="password"
          name="password"
          required
          placeholder="password"
          v-model="form.password"
        />
        <label></label>
      </div>
      <!-- Login button -->
      <button type="submit" variant="tonal">Login</button>
    </form>
  </div>
</template>

<script>
import router from "@/routers"; // Import the router instance
import axios from "axios"; // Import the Axios library for making HTTP requests

export default {
  data() {
    return {
      form: {
        email: "",
        password: "",
      },
    };
  },
  methods: {
    async formSubmit() {
      try {
        // Effectuer une requête pour obtenir le jeton CSRF
        await axios.get("/sanctum/csrf-cookie");

        // Effectuer la demande de connexion
        const response = await axios.post(
          "/api/login", // Utilisez une URL relative
          {
            email: this.form.email,
            password: this.form.password,
          },
          {
            withCredentials:true,
          }
        );

        const userData = response.data.user;
        // Stocker les données de l'utilisateur dans Vuex ou un autre gestionnaire d'état approprié
        this.$store.commit("setUserData", userData);

        localStorage.setItem("user", response.data.accessToken);

        if (userData.status === "client") {
          router.push("/dashboardclient"); // Rediriger vers le tableau de bord du client
        } else if (userData.status === "admin") {
          router.push("/dashboardadmin"); // Rediriger vers le tableau de bord de l'administrateur
        }
      } catch (error) {
        console.log("Erreur lors de la requête :", error);
        if (
          error.response &&
          error.response.data &&
          error.response.data.errors
        ) {
          console.log("Erreurs de validation :", error.response.data.errors);
        }
      }
    },
  },
};
</script>

<style>
html {
  height: 100%;
}
body {
  margin: 0;
  padding: 0;
  font-family: sans-serif;
}

.login-box {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 400px;
  padding: 40px;
  transform: translate(-50%, -50%);
  background: rgba(56, 94, 50, 0.5);
  box-sizing: border-box;
  box-shadow: 0 15px 25px rgba(0, 0, 0, 0.6);
  border-radius: 10px;
}

.login-box h2 {
  margin: 0 0 30px;
  padding: 0;
  color: #fff;
  text-align: center;
}

.login-box .user-box {
  position: relative;
}

.login-box .user-box input {
  width: 100%;
  padding: 10px 0;
  font-size: 16px;
  color: #fff;
  margin-bottom: 30px;
  border: none;
  border-bottom: 1px solid #fff;
  outline: none;
  background: transparent;
}
.login-box .user-box label {
  position: absolute;
  top: 0;
  left: 0;
  padding: 10px 0;
  font-size: 16px;
  color: #fff;
  pointer-events: none;
  transition: 0.5s;
}

.login-box .user-box input:focus ~ label,
.login-box .user-box input:valid ~ label {
  top: -20px;
  left: 0;
  color: #27f403;
  font-size: 12px;
}

button {
  background-color: #cdcbcb;
  border: none;
  border-radius: 20px;
  margin: 0 0 30px;
  width: 150px;
  height: 30px;
}
</style>
