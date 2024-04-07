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

        <form @submit.prevent="handleFilter" class="flex gap-3">
            <input
                v-model="search.name"
                type="text"
                placeholder="Search items..."
                class="px-3 py-1 w-1/4 border rounded-md focus:outline-blue-700"
            />
            <select
                v-model="search.inventoryId"
                class="px-3 py-1 w-1/4 border rounded-md focus:outline-blue-700"
            >
                <option value="">All Inventories</option>
                <option
                    v-for="inventory in inventories"
                    :key="inventory.id!"
                    :value="inventory.id"
                >
                    {{ inventory.name }}
                </option>
            </select>
            <button
                type="submit"
                class="bg-blue-700 text-white px-3 py-1 rounded-md hover:bg-blue-600 focus:outline-none focus:shadow-outline-blue active:bg-blue-800"
            >
                Filter
            </button>
            <a
                href="javascript:void(0)"
                @click="
                    () => {
                        search = { name: '', inventoryId: '' };
                        router.push('/items');
                    }
                "
                class="bg-gray-700 text-white px-3 py-1 rounded-md hover:bg-gray-600 focus:outline-none focus:shadow-outline-gray active:bg-gray-800"
            >
                Clear
            </a>
        </form>

        <div class="flex flex-col w-full">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-4 inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden">
                        <div v-if="!componentLoading && items.length > 0">
                            <table class="min-w-full">
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
                                                (route.query.page
                                                    ? parseInt(
                                                          route.query.page.toString()
                                                      )
                                                    : 1 - 1) *
                                                    10 +
                                                index +
                                                1
                                            }}
                                        </td>
                                        <td
                                            class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap"
                                        >
                                            <div
                                                class="flex items-center gap-x-3"
                                            >
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
                                                item.description.substring(
                                                    0,
                                                    35
                                                )
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
                                                    @click="
                                                        initDeleteItem(item)
                                                    "
                                                    class="font-medium text-red-600 hover:text-red-900"
                                                >
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="mt-2 flex justify-end">
                                <Pagination
                                    :currentPage="
                                        route.query.page
                                            ? parseInt(
                                                  route.query.page.toString()
                                              )
                                            : 1
                                    "
                                    :limit="10"
                                    :totalItems="totalItems"
                                    @pageChange="handlePageChange"
                                />
                            </div>
                        </div>
                        <div
                            v-else-if="componentLoading"
                            className="flex flex-col items-center justify-center h-full"
                        >
                            <div
                                className="w-24 h-24 border-8 border-dashed rounded-full animate-spin border-blue-800"
                            ></div>
                            <p class="mt-5 text-gray-500">
                                Please wait while we load all items...
                            </p>
                        </div>
                        <div
                            v-else-if="!componentLoading && items.length === 0"
                            class="text-center text-2xl text-red-500"
                        >
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
                                        deleteItem(deletedItem.id!);
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
    import { ref, onMounted, onUpdated } from 'vue';
    import { axiosPrivate } from '../api/axios';
    import Modal from '../components/Modal.vue';
    import Layout from '../components/Layout.vue';
    import formatError from '../helpers/formatError';
    import { POSITION, useToast } from 'vue-toastification';
    import { useRoute, useRouter } from 'vue-router';
    import { InventoryType, ItemType } from '../types';
    import Pagination from '../components/Pagination.vue';

    const route = useRoute();
    const router = useRouter();

    const search = ref({
        name: '',
        inventoryId: '',
    });
    const items = ref<ItemType[]>([]);
    const inventories = ref<InventoryType[]>([]);

    const deletedItem = ref<ItemType>({
        id: null,
        name: '',
        image: '',
        description: '',
        quantity: 0,
        inventory: '',
        inventory_id: null,
    });

    const totalItems = ref(0);

    const componentLoading = ref(false);
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

    const getInventories = async () => {
        try {
            const response = await axiosPrivate.get(`/inventories`);
            const data = response.data;
            inventories.value = data.data.inventories;
        } catch (error) {
            console.error(error);
        } finally {
            componentLoading.value = false;
        }
    };

    const getItems = async () => {
        try {
            componentLoading.value = true;
            let url = '/items/?';

            if (route.query.inventoryId) {
                search.value.inventoryId = route.query.inventoryId.toString();
                url += `inventoryId=${route.query.inventoryId}&`;
            }
            if (route.query.name) {
                url += `name=${route.query.name}&`;
            }
            if (route.query.page) {
                url += `page=${route.query.page}&`;
            }

            const response = await axiosPrivate.get(url);
            const data = response.data;
            items.value = data.data.items;
            totalItems.value = data.data.total;
        } catch (error) {
            console.error(error);
        } finally {
            componentLoading.value = false;
        }
    };

    const initDeleteItem = (item: ItemType) => {
        showDeleteModal.value = true;
        deletedItem.value = { ...item };
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

    const handlePageChange = (page: number) => {
        router.push({ query: { ...route.query, page: page } });
        getItems();
    };

    const handleFilter = () => {
        router.push({
            query: { ...route.query, ...search.value },
        });
    };

    onMounted(async () => {
        try {
            await getInventories();
            await getItems();
        } catch (error) {}
    });

    onUpdated(() => {
        getItems();
    });
</script>
