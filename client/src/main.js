import { createApp } from 'vue';
import App from './App.vue';
import router from './routers';
import store from './store/index';

import axios from 'axios';
axios.defaults.baseURL = "http://localhost:8000/";

import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';

const vuetify = createVuetify({
  components,
  directives,
});

const app = createApp(App);

app.use(vuetify);
app.use(router);
app.use(store); 
app.mount('#app');
