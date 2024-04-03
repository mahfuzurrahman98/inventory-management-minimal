import { defineStore } from 'pinia';

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
    },
});

export default useUserStore;
