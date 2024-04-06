<template>
    <Layout>
        <div class="flex justify-between">
            <h3 class="text-2xl">Inventories</h3>
            <button
                class="bg-blue-700 rounded text-white text-sm px-3 py-1"
                @click="showAddModal = true"
            >
                Add New
            </button>
        </div>
        <hr class="mt-3 mb-10" />
        <div class="flex flex-col w-full">
            <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-4 inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden">
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
                                        Description
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
                                    v-for="(inventory, index) in inventories"
                                    :key="inventory.id!"
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
                                        {{ inventory.name }}
                                    </td>
                                    <td
                                        class="text-sm text-left text-gray-900 font-light px-6 py-4 whitespace-break-spaces"
                                    >
                                        {{ inventory.description }}
                                    </td>
                                    <td
                                        class="text-sm text-center flex justify-center text-gray-900 font-light px-6 py-4 whitespace-nowrap"
                                    >
                                        <div class="flex gap-x-5">
                                            <router-link
                                                :to="{
                                                    name: 'items',
                                                    query: {
                                                        inventoryId:
                                                            inventory.id,
                                                    },
                                                }"
                                                class="font-medium text-green-700 hover:text-green-900"
                                            >
                                                View Items
                                            </router-link>
                                            <button
                                                @click="
                                                    initEditInventroy(inventory)
                                                "
                                                class="font-medium text-blue-600 hover:text-blue-900"
                                            >
                                                Edit
                                            </button>
                                            <button
                                                @click="
                                                    initDeleteInventory(
                                                        inventory
                                                    )
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
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Inventory Modal -->
        <Teleport to="body">
            <modal
                :show="showAddModal"
                @close="showAddModal = false"
                title="Add Inventory"
            >
                <template #body>
                    <form
                        @submit.prevent="addInventory"
                        class="flex flex-col gap-y-3"
                    >
                        <div>
                            <label class="text-base font-medium" for="name"
                                >Name:</label
                            >
                            <input
                                type="text"
                                id="name"
                                v-model="newInventory.name"
                                class="mt-1 px-3 py-1 w-full border rounded-md focus:outline-blue-700"
                                placeholder="Enter inventory name"
                                required
                            />
                        </div>
                        <div>
                            <label
                                class="text-base font-medium"
                                for="description"
                                >Description:</label
                            >
                            <input
                                type="text"
                                id="description"
                                v-model="newInventory.description"
                                class="mt-1 px-3 py-1 w-full border rounded-md focus:outline-blue-700"
                                placeholder="Enter invenotory description"
                                required
                            />
                        </div>
                        <div>
                            <button
                                type="submit"
                                :class="`bg-blue-800 text-white px-3 py-1 rounded-md text-md hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue active:bg-blue-800 ${
                                    addBtnLoading
                                        ? 'opacity-50 cursor-not-allowed'
                                        : ''
                                }`"
                                :disabled="addBtnLoading"
                            >
                                {{ addBtnLoading ? 'Loading...' : 'Save' }}
                            </button>
                        </div>
                    </form>
                </template>
            </modal>
        </Teleport>

        <!-- Edit Inventory Modal -->
        <Teleport to="body">
            <modal
                :show="showUpdateModal"
                @close="showUpdateModal = false"
                title="Edit Inventory"
            >
                <template #body>
                    <form
                        @submit.prevent="updateInventory"
                        class="flex flex-col gap-y-3"
                    >
                        <div>
                            <label class="text-base font-medium" for="name"
                                >Name:</label
                            >
                            <input
                                type="text"
                                id="editName"
                                v-model="editedInventory.name"
                                class="mt-1 px-3 py-2 w-full border rounded-md focus:outline-blue-700"
                                placeholder="Enter inventory name"
                                required
                            />
                        </div>
                        <div>
                            <label
                                class="text-base font-medium"
                                for="description"
                                >Description:</label
                            >
                            <input
                                type="text"
                                id="editDescription"
                                v-model="editedInventory.description"
                                class="mt-1 px-3 py-2 w-full border rounded-md focus:outline-blue-700"
                                required
                            />
                        </div>
                        <div>
                            <button
                                type="submit"
                                :class="`bg-blue-800 text-white px-3 py-1 rounded-md text-md hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue active:bg-blue-800 ${
                                    updateBtnLoading
                                        ? 'opacity-50 cursor-not-allowed'
                                        : ''
                                }`"
                                :disabled="updateBtnLoading"
                            >
                                {{ updateBtnLoading ? 'Loading...' : 'Update' }}
                            </button>
                        </div>
                    </form>
                </template>
            </modal>
        </Teleport>

        <!-- Delete Inventory Modal -->
        <Teleport to="body">
            <modal
                :show="showDeleteModal"
                @close="showDeleteModal = false"
                title="Delete Inventory"
            >
                <template #body>
                    <div class="flex flex-col gap-y-3">
                        <p class="text-lg">
                            Are you sure you want to delete this inventory?
                        </p>
                        <div class="flex justify-end gap-x-3">
                            <button
                                @click="
                                    () => {
                                        showDeleteModal = false;
                                        deleteInventory(deletedInventroy.id!);
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
    import { InventoryType } from '../types';

    const inventories = ref<InventoryType[]>([]);
    const newInventory = ref({ name: '', description: '' });
    const editedInventory = ref<InventoryType>({
        id: null,
        name: '',
        description: '',
    });
    const deletedInventroy = ref<InventoryType>({
        id: null,
        name: '',
        description: '',
    });
    const currentPage = ref(1);
    const limit = ref(2);
    const totalInventories = ref(0);

    const showAddModal = ref(false);
    const showUpdateModal = ref(false);
    const showDeleteModal = ref(false);

    const addBtnLoading = ref(false);
    const updateBtnLoading = ref(false);
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
            totalInventories.value = data.data.total;
        } catch (error) {
            console.error(error);
        }
    };

    const addInventory = async () => {
        try {
            addBtnLoading.value = true;

            const response = await axiosPrivate.post(
                '/inventories',
                newInventory.value
            );
            const data = response.data;

            if (data.success) {
                showAddModal.value = false;
                await getInventories();
                toast.success('Inventory added successfully', toastOptions);
            }
        } catch (err: any) {
            let errorMessage = formatError(err);

            // alert(errorMessage);
            toast.error(errorMessage, toastOptions);
        } finally {
            addBtnLoading.value = false;
        }
    };

    const initEditInventroy = (inventory: InventoryType) => {
        console.log(inventory);

        showUpdateModal.value = true;
        editedInventory.value = { ...inventory };
    };

    const updateInventory = async () => {
        try {
            updateBtnLoading.value = true;
            const response = await axiosPrivate.put(
                `/inventories/${editedInventory.value.id}`,
                editedInventory.value
            );
            const data = response.data;

            if (data.success) {
                showUpdateModal.value = false;
                await getInventories();
                toast.success('Inventory updated successfully', toastOptions);
            }
        } catch (error) {
            console.error(error);
            let errorMessage = formatError(error);
            toast.error(errorMessage, toastOptions);
        } finally {
            updateBtnLoading.value = false;
        }
    };

    const initDeleteInventory = (inventory: InventoryType) => {
        showDeleteModal.value = true;
        deletedInventroy.value = { ...inventory };
    };

    const deleteInventory = async (id: Number) => {
        try {
            const response = await axiosPrivate.delete(`/inventories/${id}`);
            const data = response.data;

            if (data.success) {
                await getInventories();
                toast.success('Inventory deleted successfully', toastOptions);
            }
        } catch (error) {
            console.error(error);
            let errorMessage = formatError(error);
            toast.error(errorMessage, toastOptions);
        } finally {
            deleteBtnLoading.value = false;
        }
    };

    onMounted(getInventories);
</script>
