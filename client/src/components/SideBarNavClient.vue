<template>
  <v-navigation-drawer app>
    <v-list>
      <v-list-item link>
        <v-list-item-icon>
          <v-icon>mdi-home</v-icon>
        </v-list-item-icon>
        <v-list-item-title>Données personnelles</v-list-item-title>
      </v-list-item>
      <v-list-item link>
        <v-list-item-icon>
          <v-icon>mdi-account</v-icon>
        </v-list-item-icon>
        <v-list-item-title>Portefeuille</v-list-item-title>
      </v-list-item>
      <v-list-item link>
        <v-list-item-icon>
          <v-icon>mdi-settings</v-icon>
        </v-list-item-icon>
        <v-list-item-title>Cours des cryptos</v-list-item-title>
      </v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
export default {
  name: 'SidebarNavClient'
};
</script>
