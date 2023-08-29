import { createStore } from 'vuex';

export default createStore({
  state: {
    userData: null, // Initial user data is null
  },
  mutations: {
    setUserData(state, userData) {
      state.userData = userData;
    },
  },
  // ... other store configuration
});
