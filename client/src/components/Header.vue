<template>
    <div class="bg-white shadow">
        <nav
            class="w-full flex justify-between items-center mx-auto h-14 max-w-6xl px-4"
        >
            <div class="flex items-center">
                <router-link
                    to="/"
                    class="text-2xl font-semibold text-blue-800 leading-none"
                >
                    Inventrory Management
                </router-link>
            </div>
            <div
                v-if="auth.token !== ''"
                class="flex gap-x-2 justify-end items-center"
            >
                <div class="px-2 bg-gray-200 py-1">Welcome, Arif Rahman</div>
                <div class="flex items-center">
                    <button
                        @click="handleLogout"
                        class="bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-500"
                    >
                        Logout
                    </button>
                </div>
            </div>

            <div v-else class="flex justify-end items-center">
                <div class="flex items-center">
                    <router-link
                        to="/login"
                        class="bg-blue-800 text-white px-3 py-1 rounded-md hover:bg-blue-700"
                    >
                        Login
                    </router-link>
                </div>
            </div>
        </nav>
    </div>
</template>

<script setup lang="ts">
    import useAuthStore from '../stores/auth';
    import { useRouter } from 'vue-router';

    const auth = useAuthStore();
    const router = useRouter();

    const handleLogout = async () => {
        try {
            console.log('handleLogout');
            await auth.logout();

            router.push({ name: 'login' });
        } catch (error: any) {
            console.error(error);
        }
    };
</script>
