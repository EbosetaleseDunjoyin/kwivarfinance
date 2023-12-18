import './bootstrap';

import Alpine from 'alpinejs';

import { createApp } from "vue";

import HomeView from "./Vue/HomeView.vue";

window.Alpine = Alpine;

Alpine.start();

const app = createApp({});
app.component("homeview", HomeView); 
app.mount("#app");