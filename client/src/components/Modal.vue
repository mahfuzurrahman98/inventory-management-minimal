<script setup>
    const props = defineProps({
        show: Boolean,
        title: String,
    });
</script>

<template>
    <Transition name="modal">
        <div v-if="show">
            <div class="fixed inset-0 z-10 bg-black opacity-50"></div>
            <div class="fixed inset-0 z-20 flex items-center justify-center">
                <div class="bg-white rounded-lg shadow-lg w-3/4 md:w-1/2 lg:w-1/3">
                    <div class="flex justify-between items-center p-3 border-b border-gray-200">
                        <h2 class="text-xl font-semibold">{{ title }}</h2>
                        <button @click="$emit('close')" class="text-gray-600 hover:bg-gray-200 px-3 py-1 rounded-full">
                            &times;
                        </button>
                    </div>
                    <div class="p-6">
                        <slot name="body">default body</slot>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>