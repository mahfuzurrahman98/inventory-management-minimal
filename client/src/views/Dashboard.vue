<template>
    <Layout>
        <h1>Hi, {{ auth.token }}</h1>
    </Layout>
</template>

<script setup>
    import Layout from '../components/Layout.vue';
    import useAuthStore from '../stores/auth';

    const auth = useAuthStore();
</script>
