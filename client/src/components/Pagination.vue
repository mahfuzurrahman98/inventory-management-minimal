<template>
    <div class="flex justify-between items-center gap-5">
        <div>
            Showing <span class="font-semibold">{{ limit }}</span> per page
        </div>
        <div class="flex justify-center items-center">
            <button
                @click="prevPage"
                :disabled="currentPage === 1"
                class="px-3 py-1 rounded mr-2 text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50"
                :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
            >
                Prev
            </button>
            <div class="text-black">
                Page: {{ currentPage }} / {{ totalPages }}
            </div>
            <button
                @click="nextPage"
                :disabled="currentPage === totalPages"
                class="px-3 py-1 rounded ml-2 text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50"
                :class="{
                    'opacity-50 cursor-not-allowed': currentPage === totalPages,
                }"
            >
                Next
            </button>
        </div>
    </div>
</template>

<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        currentPage: Number,
        limit: Number,
        totalItems: Number,
    });

    console.log('on pagination:', props.currentPage, props.totalItems);
    const totalPages = Math.ceil(props.totalItems / props.limit);
    const emit = defineEmits(['pageChange']);

    const prevPage = () => {
        if (props.currentPage > 1) {
            emit('pageChange', props.currentPage - 1);
        }
    };

    const nextPage = () => {
        if (props.currentPage < totalPages) {
            console.log('next page');
            emit('pageChange', props.currentPage + 1);
        }
    };
</script>
