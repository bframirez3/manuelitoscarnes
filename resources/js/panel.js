import './bootstrap';
import { createApp } from "vue";
import AppComponent  from "./comp/app.vue";
import vuetify from "./vuetify";
import panel from './comp/container.vue';
import router from './rutas'

const app  = createApp(panel);

app.use(vuetify);
app.use(router);
app.use(panel);
app.mount("#panel");