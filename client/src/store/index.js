// Import the createStore function from the Vuex library
import { createStore } from "vuex";

// Vuex store instance
export default createStore({
  state: {
    userData: null, // State property to store user data
  },
  mutations: {
    // Mutation to set user data in the state
    setUserData(state, userData) {
      state.userData = userData;
    },
  },
  actions: {
    // Action to update user data
    updateUserData(context, userData) {
      context.commit("setUserData", userData);
    },
  },
  getters: {
    // Getter to access user data
    getUserData(state) {
      return state.userData;
    },
  },
});
