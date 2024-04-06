<template>
    <div
        class="flex items-center justify-center min-h-screen bg-black text-white"
    >
        <div class="rounded flex flex-col justify-center text-center">
            <h2 class="text-3xl font-semibold mb-4">404 | Not Found</h2>
            <p class="text-gray-200 mb-4">
                The page you are looking for does not exist.
            </p>

            <router-link
                to="/"
                class="flex justify-center items-center gap-x-2 bg-white py-1 text-black rounded"
            >
                Back to Home
            </router-link>
        </div>
    </div>
</template>
