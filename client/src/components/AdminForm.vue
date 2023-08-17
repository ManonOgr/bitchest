<template>
  <v-container fluid fill-height>
    <v-row justify="center" align-content="center">
      <v-col cols="12" sm="8" md="6">
        <v-card>
          <v-card-title class="justify-center text-center">
            <img src="@/assets/bitchest_logo.png" alt="Logo" height="100">
          </v-card-title>
          <v-card-text>
            <v-form @submit.prevent="submitForm">
              <v-text-field
                v-model="email"
                label="Email"
                type="email"
                required
              ></v-text-field>
              <v-text-field
                v-mode.l="password"
                label="Password"
                type="password"
                required
              ></v-text-field>
              <v-btn color="primary" type="submit" class="text-center">Submit</v-btn>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>
