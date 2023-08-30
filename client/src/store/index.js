// Import the createStore function from Vuex library
import { createStore } from "vuex";

// Create and export a Vuex store instance
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
});
