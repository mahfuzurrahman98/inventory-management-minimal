<template>
    <Layout>
        <div class="flex items-center justify-center mt-8 lg:mt-24">
            <div class="bg-white p-6 md:px-8 rounded shadow-md w-96">
                <h1 class="text-2xl font-semibold mb-6">Login to start</h1>

                <div
                    v-if="error"
                    class="bg-red-500 text-white px-3 py-1 rounded-md mb-4"
                >
                    {{ error }}

                    <button
                        class="float-right focus:outline-none"
                        @click="error = ''"
                    >
                        <span class="font-semibold"> &times; </span>
                    </button>
                </div>

                <form @submit.prevent="handleSubmit">
                    <div class="mb-4">
                        <label for="email" class="block text-md font-semibold">
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
                            class="block text-md font-semibold"
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
</template>

<script setup>
    import Layout from '../components/Layout.vue';
    import { ref } from 'vue';

    const error = ref('');
    const loading = ref(false);
    const formData = ref({
        email: '',
        password: '',
    });

    const handleSubmit = () => {
        // log the data
        console.log(formData.value);
    };

    const handleLogout = () => {
        console.log('handleLogout');
    };
</script>
