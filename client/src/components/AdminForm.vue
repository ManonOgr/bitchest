<script>
import router from "@/routers";
import axios from "axios";

//function for login
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
    formSubmit() {
      try {
        axios
          .post(
            "http://127.0.0.1:8000/api/login",
            {
              email: this.form.email,
              password: this.form.password,
            },
            {
              // withCredentials: true,
              headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                "Access-Control-Allow-Origin": "*",
              },
            }
          )
          .then((res) => {
            console.log(res);
            if (res.data.user.status === "client") {
              router.push("/dashboardclient");
            } else if (res.data.user.status === "admin") {
              router.push("/dashboardadmin");
            }
          });

        //unable to connect
      } catch (error) {
        console.log(error);
      }
    },
  },
};
</script>

<template>
  <div class="login-box">
    <img
      src="../assets/bitchest_logo.png"
      alt="logo"
      style="max-width: 100%; height: auto"
    />
    <form>
      <div class="user-box">
        <input
          type="email"
          name="email"
          required=""
          placeholder="email"
          v-model="form.email"
        />
        <label></label>
      </div>
      <div class="user-box">
        <input
          type="password"
          name="password"
          required=""
          placeholder="password"
          v-model="form.password"
        />
        <label></label>
      </div>
      <v-btn variant="tonal" @click.prevent="formSubmit">Login</v-btn>
    </form>
  </div>
</template>

<style>
html {
  height: 100%;
}
body {
  margin: 0;
  padding: 0;
  font-family: sans-serif;
  background: linear-gradient(#80b918, #80ed99);
}

.login-box {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 400px;
  padding: 40px;
  transform: translate(-50%, -50%);
  background: rgba(0, 0, 0, 0.5);
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
  color: #03e9f4;
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
