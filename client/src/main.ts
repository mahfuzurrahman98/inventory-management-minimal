import { createApp } from 'vue';
import './style.css';
import App from './App.vue';
import { createPinia } from 'pinia';
import router from './routes';
import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';
import useAuthStore from './stores/auth';

const pinia = createPinia();
const app = createApp(App);

app.use(pinia);

const authStore = useAuthStore();

window.onload = () => {
    // console.log('Welcome to the Inventory App!');
    if (authStore.token !== '') {
        // an authenticated user is present
        localStorage.setItem('token', authStore.token);
    } else {
        localStorage.removeItem('token');
    }
};

app.use(router);
app.use(Toast);

app.mount('#app');
