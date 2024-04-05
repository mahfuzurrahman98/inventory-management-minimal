import { createApp } from 'vue';
import './style.css';
import App from './App.vue';
import { createPinia } from 'pinia';
import router from './routes';
import useAuthStore from './stores/auth';
import { setupAxiosInterceptors } from './api/axios';
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';

const pinia = createPinia();
const app = createApp(App);

app.use(pinia);
app.use(Toast);

const authStore = useAuthStore();
setupAxiosInterceptors(authStore);


authStore.refresh().catch((error) => {
    console.error(error);
});


app.mount('#app');

app.use(router);