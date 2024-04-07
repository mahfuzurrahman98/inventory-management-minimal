<template>
    <div
        v-if="isRefreshing"
        className="flex flex-col items-center justify-center h-screen"
    >
        <div
            className="w-32 h-32 border-8 border-dashed rounded-full animate-spin border-blue-800"
        ></div>
    </div>
    <router-view v-else></router-view>
</template>

<script setup>
    import { onMounted, ref } from 'vue';
    import { useRouter } from 'vue-router';
    import useAuthStore from './stores/auth';
    import { setupAxiosInterceptors } from './api/axios';

    const router = useRouter();

    const isRefreshing = ref(false);
    const authStore = useAuthStore();

    onMounted(async () => {
        setupAxiosInterceptors(authStore);

        try {
            isRefreshing.value = true;
            const token = await authStore.refresh();
            localStorage.setItem('token', token);
            if (authStore.token) {
                // await router.push({ name: 'inventories' });
                // push to the current page
                // router.go(router.currentRoute.value.path);
            }
        } catch (error) {
            console.error('refresh error:', error);
        } finally {
            isRefreshing.value = false;
        }
    });
</script>
