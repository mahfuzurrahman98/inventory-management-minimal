import { createApp } from 'vue';
import './style.css';
import App from './App.vue';
import { createPinia } from 'pinia';
import router from './routes';
import useAuthStore from './stores/auth';
import { setupAxiosInterceptors } from './api/axios';

const pinia = createPinia();
const app = createApp(App);

app.use(pinia);
app.use(router);

const authStore = useAuthStore();
setupAxiosInterceptors(authStore);

app.mount('#app');
