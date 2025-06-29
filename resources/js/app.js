import './bootstrap';
import { createApp } from "vue";
import AppComponent  from "./comp/app.vue";
import vuetify from "./vuetify";
import container from './comp/app.vue';
import router from './rutas'

const app  = createApp(container);

app.use(vuetify);
app.use(router);
app.use(container);
app.mount("#container");