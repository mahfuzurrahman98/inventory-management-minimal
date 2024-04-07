<template>
    <Layout>
        <div class="flex justify-between">
            <h3 class="text-2xl">Items</h3>
            <router-link
                to="/items/create"
                class="bg-blue-700 rounded text-white text-sm px-3 py-1"
            >
                Add New
            </router-link>
        </div>
        <hr class="mt-3 mb-10" />
        <div class="flex flex-col w-full">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-4 inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden">
                        <table v-if="items.length > 0" class="min-w-full">
                            <thead class="border-b bg-gray-50">
                                <tr>
                                    <th
                                        scope="col"
                                        class="text-sm text-left font-medium text-gray-900 px-6 py-4"
                                    >
                                        #
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-sm text-left font-medium text-gray-900 px-6 py-4"
                                    >
                                        Name
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-sm text-left font-medium text-gray-900 px-6 py-4"
                                    >
                                        Inventory
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-sm text-left font-medium text-gray-900 px-6 py-4"
                                    >
                                        Description
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-sm text-left font-medium text-gray-900 px-6 py-4"
                                    >
                                        Quantity
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-sm text-left font-medium text-gray-900 px-6 py-4"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(item, index) in items"
                                    :key="item.id!"
                                    class="bg-white border-b"
                                >
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                    >
                                        {{
                                            (currentPage - 1) * limit +
                                            index +
                                            1
                                        }}
                                    </td>
                                    <td
                                        class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap"
                                    >
                                        <div class="flex items-center gap-x-3">
                                            <img
                                                v-if="
                                                    item.image &&
                                                    typeof item.image ===
                                                        'string'
                                                "
                                                :src="item.image"
                                                alt="item.name"
                                                class="w-10 h-10 rounded-sm"
                                            />
                                            <p>{{ item.name }}</p>
                                        </div>
                                    </td>
                                    <td
                                        class="text-sm text-left text-gray-900 font-light px-6 py-4 whitespace-nowrap"
                                    >
                                        {{ item.inventory }}
                                    </td>
                                    <td
                                        class="text-sm text-left text-gray-900 font-light px-6 py-4 whitespace-break-spaces"
                                    >
                                        {{
                                            item.description.substring(0, 35)
                                        }}...
                                    </td>
                                    <td
                                        class="text-sm text-left text-gray-900 font-light px-6 py-4"
                                    >
                                        {{ item.quantity }}
                                    </td>
                                    <td
                                        class="text-sm text-center flex text-gray-900 font-light px-6 py-4 whitespace-nowrap"
                                    >
                                        <div class="flex gap-x-5">
                                            <router-link
                                                :to="`/items/${item.id}/edit`"
                                                class="font-medium text-blue-600 hover:text-blue-900"
                                            >
                                                Edit
                                            </router-link>
                                            <button
                                                @click="initDeleteItem(item)"
                                                class="font-medium text-red-600 hover:text-red-900"
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div v-else class="text-center text-2xl text-red-500">
                            No items found
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Item Modal -->
        <Teleport to="body">
            <modal
                :show="showDeleteModal"
                @close="showDeleteModal = false"
                title="Delete Item"
            >
                <template #body>
                    <div class="flex flex-col gap-y-3">
                        <p class="text-lg">
                            Are you sure you want to delete this item?
                        </p>
                        <div class="flex justify-end gap-x-3">
                            <button
                                @click="
                                    () => {
                                        showDeleteModal = false;
                                        deleteItem(deletedInventroy.id!);
                                    }
                                "
                                class="bg-red-700 text-white px-3 py-1 rounded-md text-md hover:bg-red-600 focus:outline-none focus:shadow-outline-red active:bg-red-800"
                            >
                                Yes
                            </button>
                            <button
                                @click="showDeleteModal = false"
                                class="bg-gray-700 text-white px-3 py-1 rounded-md text-md hover:bg-gray-600 focus:outline-none focus:shadow-outline-gray active:bg-gray-800"
                            >
                                Cancel
                            </button>
                        </div>
                    </div>
                </template>
            </modal>
        </Teleport>
    </Layout>
</template>

<script setup lang="ts">
    import { ref, onMounted } from 'vue';
    import { axiosPrivate } from '../api/axios';
    import Modal from '../components/Modal.vue';
    import Layout from '../components/Layout.vue';
    import formatError from '../helpers/formatError';
    import { POSITION, useToast } from 'vue-toastification';
    import { useRoute } from 'vue-router';
    import { ItemType } from '../types';

    const route = useRoute();

    const items = ref<ItemType[]>([]);

    const deletedInventroy = ref<ItemType>({
        id: null,
        name: '',
        image: '',
        description: '',
        quantity: 0,
        inventory: '',
        inventory_id: null,
    });

    const currentPage = ref(1);
    const limit = ref(2);
    const totalItems = ref(0);

    const queryParams = ref({
        page: 1,
        limit: 10,
        inventoryId: route.query.inventoryId,
    });

    const showDeleteModal = ref(false);

    const deleteBtnLoading = ref(false);

    const toast = useToast();
    const toastOptions: any = {
        position: POSITION.BOTTOM_RIGHT,
        timeout: 5000,
        closeOnClick: false,
        pauseOnFocusLoss: true,
        pauseOnHover: true,
        draggable: false,
        draggablePercent: 0.6,
        showCloseButtonOnHover: false,
        hideProgressBar: false,
        closeButton: 'button',
        icon: true,
        rtl: false,
    };

    const getItems = async () => {
        try {
            const response = await axiosPrivate.get(
                `/items${
                    queryParams.value.inventoryId
                        ? `?inventoryId=${queryParams.value.inventoryId}`
                        : ''
                }`
            );
            const data = response.data;
            items.value = data.data.items;
            totalItems.value = data.data.total;
        } catch (error) {
            console.error(error);
        }
    };

    const initDeleteItem = (item: ItemType) => {
        showDeleteModal.value = true;
        deletedInventroy.value = { ...item };
    };

    const deleteItem = async (id: Number) => {
        try {
            const response = await axiosPrivate.delete(`/items/${id}`);
            const data = response.data;

            if (data.success) {
                await getItems();
                toast.success('Item deleted successfully', toastOptions);
            }
        } catch (error) {
            console.error(error);
            let errorMessage = formatError(error);
            toast.error(errorMessage, toastOptions);
        } finally {
            deleteBtnLoading.value = false;
        }
    };

    onMounted(getItems);
</script>
