// Importez la fonction createStore de la bibliothèque Vuex
import { createStore } from "vuex";

// Créez et exportez une instance du store Vuex
export default createStore({
  state: {
    userData: null, // Propriété d'état pour stocker les données de l'utilisateur
  },
  mutations: {
    // Mutation pour définir les données de l'utilisateur dans l'état
    setUserData(state, userData) {
      state.userData = userData;
    },
  },
  actions: {
    // Action pour mettre à jour les données de l'utilisateur
    updateUserData(context, userData) {
      context.commit("setUserData", userData);
    },
  },
  getters: {
    // Getter pour accéder aux données de l'utilisateur
    getUserData(state) {
      return state.userData;
    },
  },
});
