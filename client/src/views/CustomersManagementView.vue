<template>
  <v-app>
    <sidebar-nav></sidebar-nav>

    <v-app-bar app>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title>Gestion des clients</v-toolbar-title>
    </v-app-bar>

    <v-main>
      <v-container>
        <div class="container-tab-admin">
          <div class="tab">
            <v-table height="300px">
              <thead>
                <tr>
                  <th class="text-left">Nom de l'utilisateur</th>
                  <th class="text-left">Prénom de l'utilisateur</th>
                  <th class="text-left">Modifier</th>
                  <th class="text-left">Supprimer</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Quentin</td>
                  <td>Leroy</td>
                  <td>
                    <v-btn variant="tonal"> Modifier </v-btn>
                  </td>
                  <td>
                    <v-btn variant="tonal"> Supprimer </v-btn>
                  </td>
                </tr>
              </tbody>
            </v-table>
          </div>
        </div>
        <p>Contenu Page Gestions des clients</p>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import SidebarNav from "@/components/SideBarNav.vue";

export default {
  components: {
    SidebarNav,
  },
  data() {
    return {
      drawer: false,
    };
  },
};
</script>

<style>
.container-tab-admin {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  margin-top: 50px;
}

.tab {
  margin-top: 50px;
}
</style>
