<template>
    <Layout>
        <div class="flex items-center justify-center mt-8 lg:mt-24">
            <div class="bg-white p-6 md:px-8 rounded shadow-md w-96">
                <h1 class="text-2xl font-medium mb-6">Login to start</h1>

                <div
                    v-if="error != ''"
                    class="bg-red-500 text-white px-3 py-1 rounded-md mb-4"
                >
                    {{ error }}
                    <button
                        class="float-right focus:outline-none"
                        @click="error = ''"
                    >
                        <span class="font-medium"> &times; </span>
                    </button>
                </div>

                <form @submit.prevent="handleLogin">
                    <div class="mb-4">
                        <label for="email" class="block text-base font-medium">
                            Email
                        </label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            v-model="formData.email"
                            class="mt-1 px-3 py-2 w-full border rounded-md focus:outline-blue-700"
                            placeholder="Enter your email"
                            autocomplete="email"
                            required
                        />
                    </div>
                    <div class="mb-4">
                        <label
                            for="password"
                            class="block text-base font-medium"
                        >
                            Password
                        </label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            v-model="formData.password"
                            class="mt-1 px-3 py-2 w-full border rounded-md focus:outline-blue-700"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        />
                    </div>
                    <button
                        type="submit"
                        :class="`w-full bg-blue-800 text-white px-3 py-2 rounded-md text-md hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue active:bg-blue-800 ${
                            loading ? 'opacity-50 cursor-not-allowed' : ''
                        }`"
                        :disabled="loading"
                    >
                        {{ loading ? 'Loading...' : 'Login' }}
                    </button>
                </form>

                <div class="text-center mt-5">
                    <p class="text-gray-600 text-md font-sm">
                        Don't have an account?
                        <span>
                            <router-link class="text-blue-800" to="/register">
                                Register
                            </router-link>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </Layout>
    <Toaster />
</template>

<script setup lang="ts">
    import axios from '../api/axios';
    import Layout from '../components/Layout.vue';
    import useAuthStore from '../stores/auth';
    import { ref } from 'vue';
    import { useRouter } from 'vue-router';
    import { POSITION, useToast } from 'vue-toastification';

    const error = ref('');
    const loading = ref(false);
    const formData = ref({
        email: '',
        password: '',
    });

    const router = useRouter();

    const toast = useToast();

    const handleLogin = async () => {
        const authStore = useAuthStore();
        try {
            loading.value = true;
            const response = await axios.post('/auth/login', formData.value);
            console.log(response);
            const data = await response.data;
            if (data.success) {
                authStore.setAuth({
                    token: data.data.accessToken,
                    name: data.data.user.name,
                });
                localStorage.setItem('token', data.data.accessToken);

                toast.success('Login successful', {
                    position: POSITION.BOTTOM_RIGHT,
                    timeout: 3000,
                    closeOnClick: false,
                    pauseOnFocusLoss: true,
                    pauseOnHover: true,
                    draggable: false,
                    draggablePercent: 0.6,
                    showCloseButtonOnHover: false,
                    hideProgressBar: false,
                    closeButton: 'button',
                    icon: true,
                    rtl: false,
                });

                router.push({ name: 'inventories' });
            } else {
                console.log(data);
                error.value = 'Invalid credentials';
            }
        } catch (err: any) {
            if (err.response.status == 422) {
                if (typeof err.response.data.message === 'string') {
                    error.value = err.response.data.message;
                } else {
                    // if the message is an object, then we need to get the first key-value pair
                    const key = Object.keys(err.response.data.message)[0];
                    error.value = err.response.data.message[key][0];
                }
            } else {
                error.value = err.response.data.message;
            }
        } finally {
            loading.value = false;
        }
    };
</script>
