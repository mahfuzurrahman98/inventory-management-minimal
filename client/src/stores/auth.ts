import { defineStore } from 'pinia';
import axios from '../api/axios';

interface IAuth {
    token: string;
    name: string;
}

const useUserStore = defineStore('auth', {
    state: () => {
        return {
            token: '',
            name: '',
        };
    },
    actions: {
        setAuth(auth: IAuth) {
            this.token = auth.token;
            this.name = auth.name;
        },
        async logout() {
            try {
                await axios.post('/auth/logout', null, {
                    headers: { 'Content-Type': 'application/json' },
                    withCredentials: true,
                });
                this.setAuth({
                    token: '',
                    name: '',
                });
            } catch (err) {
                console.error(err);
            }
        },
        async refresh() {
            try {
                const response = await axios.post('/auth/refresh', null, {
                    headers: { 'Content-Type': 'application/json' },
                    withCredentials: true,
                });
                console.log('refresh: ', response);
                const data = response.data.data;

                this.setAuth({
                    token: data.accessToken,
                    name: data.user.name,
                });

                return data.accessToken;
            } catch (error) {
                await this.logout();
                console.log('error from refresh function', error);
                throw error;
            }
        },
    },
});

export default useUserStore;
