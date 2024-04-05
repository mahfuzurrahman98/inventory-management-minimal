<template>
    <Layout>
        <div class="container mx-auto mt-10 text-center">
            <h1 class="text-4xl text-green-800 font-bold mb-6">
                Welcome to Our Project
            </h1>
            <p class="text-lg text-gray-600 mb-8">
                Welcome to our project! Easily manage and analyze your data for
                a better understanding.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1: Calculate Finance -->
                <div class="px-4 py-8 border rounded-md shadow-md bg-white">
                    <h2 class="text-xl text-green-700 font-semibold mb-4">
                        Feature 1
                    </h2>
                    <p class="text-gray-700 mb-4">Description for feature 1.</p>
                    <router-link
                        to="/register"
                        class="bg-green-700 text-white px-3 py-1 rounded-md text-md hover:bg-green-600 focus:outline-none focus:shadow-outline-green active:bg-green-800"
                    >
                        Get Started &rarr;
                    </router-link>
                </div>

                <!-- Feature 2: Dashboard -->
                <div class="px-4 py-8 border rounded-md shadow-md bg-white">
                    <h2 class="text-xl text-green-700 font-semibold mb-4">
                        Dashboard
                    </h2>
                    <p class="text-gray-700 mb-4">
                        View your current data on the dashboard.
                    </p>
                    <router-link
                        to="/dashboard"
                        class="bg-green-700 text-white px-3 py-1 rounded-md text-md hover:bg-green-600 focus:outline-none focus:shadow-outline-green active:bg-green-800"
                    >
                        Explore Dashboard &rarr;
                    </router-link>
                </div>

                <!-- Feature 3: Visualization -->
                <div class="px-4 py-8 border rounded-md shadow-md bg-white">
                    <h2 class="text-xl text-green-700 font-semibold mb-4">
                        Visualization
                    </h2>
                    <p class="text-gray-700 mb-4">
                        Visualize your data with interactive charts and graphs
                        for a better understanding of your situation.
                    </p>
                    <router-link
                        to="/dashboard"
                        class="bg-green-700 text-white px-3 py-1 rounded-md text-md hover:bg-green-600 focus:outline-none focus:shadow-outline-green active:bg-green-800"
                    >
                        View Charts &rarr;
                    </router-link>
                </div>
            </div>
        </div>
    </Layout>
</template>

<script setup>
    import Layout from '../components/Layout.vue';
</script>
