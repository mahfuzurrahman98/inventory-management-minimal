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

authStore
    .refresh()
    .then(() => {
        console.log('token is refreshed');
    })
    .catch((error) => {
        console.error('refresh error:', error);
    })
    .finally(() => {
        app.use(router);
        app.mount('#app');
    });
