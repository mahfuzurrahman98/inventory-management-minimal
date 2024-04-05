<template>
    <div class="flex justify-between">
        <h3 class="text-3xl">Categories</h3>
        <button
            class="bg-blue-700 rounded text-white px-3"
            data-te-toggle="modal"
            data-te-target="#addModal"
            data-te-ripple-init
            data-te-ripple-color="light"
        >
            Add New
        </button>
    </div>
    <hr class="mt-3 mb-10" />
    <div class="flex flex-col w-full">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-4 inline-block min-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full text-center">
                        <thead class="border-b bg-gray-50">
                            <tr>
                                <th
                                    scope="col"
                                    class="text-sm font-medium text-gray-900 px-6 py-4"
                                >
                                    #
                                </th>
                                <th
                                    scope="col"
                                    class="text-sm font-medium text-gray-900 px-6 py-4"
                                >
                                    Name
                                </th>
                                <th
                                    scope="col"
                                    class="text-sm font-medium text-gray-900 px-6 py-4"
                                >
                                    Description
                                </th>
                                <th
                                    scope="col"
                                    class="text-sm font-medium text-gray-900 px-6 py-4"
                                >
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="inventory in inventories"
                                class="bg-white border-b"
                            >
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                >
                                    1
                                </td>
                                <td
                                    class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap"
                                >
                                    {{ inventory.name }}
                                </td>
                                <td
                                    class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap"
                                >
                                    <div
                                        class="form-check flex items-center justify-center"
                                    >
                                        {{ inventory.description }}
                                    </div>
                                </td>
                                <td
                                    class="text-sm text-center flex justify-center text-gray-900 font-light px-6 py-4 whitespace-nowrap"
                                >
                                    <div class="flex gap-x-5"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import { axiosPrivate } from '../api/axios';

    const inventories = ref([]);

    onMounted(async () => {
        const response = await axiosPrivate.get('/inventories');
        const data = await response.data;
        const msg = await response.data.message;
        const respData = await response.data.data;

        inventories.value = respData.inventories;
    });
</script>
