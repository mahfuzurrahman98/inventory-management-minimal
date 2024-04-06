<template>
    <div class="flex justify-center items-center">
        <button
            @click="prevPage"
            :disabled="currentPage === 1"
            class="px-4 py-2 rounded bg-gray-300 mr-2"
        >
            Prev
        </button>
        <div>Page: {{ currentPage }} / {{ totalPages }}</div>
        <button
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="px-4 py-2 rounded bg-gray-300 ml-2"
        >
            Next
        </button>
    </div>
</template>

<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        currentPage: Number,
        limit: Number,
        totalItems: Number,
    });

    console.log(props.currentPage);
    console.log(props.totalItems);
    const totalPages = Math.ceil(props.totalItems / props.limit);
    // const totalPages = 78;

    const prevPage = () => {
        if (currentPage > 1) {
            emit('pageChange', currentPage - 1);
        }
    };

    const nextPage = () => {
        if (currentPage < totalPages.value) {
            emit('pageChange', currentPage + 1);
        }
    };
</script>
