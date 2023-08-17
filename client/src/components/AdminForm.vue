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
          .post("/login", {
            email: this.form.email,
            password: this.form.password,
          })
          //successful connection
          .then(() => {
              //If the connection is successful then it redirects to the admin page
            router.push("/dashboardadmin");  
          })

        //unable to connect
      } catch (error) {
        console.log(error);
      }
    },
  },
  logout() {
    localStorage.removeItem('admin');
  },
};
</script> 

<template>
  <v-container fluid fill-height>
    <v-row justify="center" align-content="center">
      <v-col cols="12" sm="8" md="6">
        <v-card>
          <v-card-title class="justify-center text-center">
            <img src="@/assets/bitchest_logo.png" alt="Logo" height="100">
          </v-card-title>
          <v-card-text>
            <v-form>
              <v-text-field
             
                label="Email"
                type="email"
                required
              ></v-text-field>
              <v-text-field
        
                label="Password"
                type="password"
                required
              ></v-text-field>
              <v-btn color="primary" type="submit" class="text-center" @click.prevent="formSubmit">Submit</v-btn>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>
