<script setup>
import AdminForm from "@/components/AdminForm.vue"; // Importing the AdminForm component
</script>

<template>
  <!-- Fluid container taking full height -->
  <v-container fluid fill-height>
    <!-- Centered row for content -->
    <v-row justify="center">
      <!-- Column for the form -->
      <v-col cols="12" sm="8" md="6">
        <!-- Rendering the AdminForm component -->
        <AdminForm />
      </v-col>
    </v-row>
  </v-container>
</template>
