<template>
  <!-- Vue application -->
  <v-app>
    <v-main>
      <v-container>
        <v-card>
          <!-- Card title showing Admin Information -->
          <v-card-title
            >Information: {{ firstName }} {{ lastName }}</v-card-title
          >
          <v-card-text>
            <!-- Display user details -->
            <p><strong>Nom:</strong> {{ lastName }}</p>
            <p><strong>Prénom:</strong> {{ firstName }}</p>
            <p><strong>Email:</strong> {{ email }}</p>
            <p><strong>Status:</strong> {{ userStatus }}</p>
          </v-card-text>
        </v-card>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
export default {
  data() {
    return {
      lastName: "", // Initialize last name
      firstName: "", // Initialize first name
      email: "", // Initialize email
      userStatus: "", // Initialize user status
    };
  },
  mounted() {
    // Assume you have stored user data in a Vuex store or similar
    this.lastName = this.$store.state.userData.last_name; // Set last name from stored data
    this.firstName = this.$store.state.userData.first_name; // Set first name from stored data
    this.email = this.$store.state.userData.email; // Set email from stored data
    this.userStatus = this.$store.state.userData.status; // Set user status from stored data
  },
};
</script>
