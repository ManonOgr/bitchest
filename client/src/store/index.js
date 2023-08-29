import { createStore } from "vuex";

export default createStore({
  state: {
    userData: null,
  },
  mutations: {
    setUserData(state, userData) {
      state.userData = userData;
    },
  },
});
