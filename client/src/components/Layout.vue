<template>
    <div class="flex flex-col min-h-screen">
        <Header />
        <div class="max-w-6xl w-full mt-5 flex flex-col mx-auto px-4">
            <slot />
        </div>
        <Footer />
    </div>
</template>

<script>
    import Header from './Header.vue';
    import Footer from './Footer.vue';

    export default {
        setup() {
            return {
                auth: {
                    token: '',
                    name: '',
                },
                handleLogout() {
                    // handle logout
                },
            };
        },
    };
</script>
