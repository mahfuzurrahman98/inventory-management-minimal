<template>
    <div class="flex justify-between">
        <h3 class="text-3xl">Inventories</h3>
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
                                :key="inventory.id"
                                class="bg-white border-b"
                            >
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                >
                                    {{
                                        (currentPage - 1) * pageSize +
                                        $index +
                                        1
                                    }}
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
                                    <div class="flex gap-x-5">
                                        <button
                                            @click="editInventory(inventory)"
                                            class="text-blue-600 hover:text-blue-900"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            @click="
                                                deleteInventory(inventory.id)
                                            "
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <nav class="mt-4" aria-label="Pagination">
                        <pagination
                            :currentPage="currentPage"
                            :pageSize="pageSize"
                            :totalItems="totalInventories"
                            @pageChange="handlePageChange"
                        />
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <button id="show-modal" @click="showAddModal = true">Show Modal</button>

    <!-- Add Inventory Modal -->
    <Teleport to="body">
        <modal
            :show="showAddModal"
            @close="showAddModal = false"
            title="Add Inventory"
        >
            <template #body>
                <h3>
                    <form @submit.prevent="addInventory">
                        <div>
                            <label for="name">Name:</label>
                            <input
                                type="text"
                                id="name"
                                v-model="newInventory.name"
                                required
                            />
                        </div>
                        <div>
                            <label for="description">Description:</label>
                            <input
                                type="text"
                                id="description"
                                v-model="newInventory.description"
                                required
                            />
                        </div>
                        <button type="submit">Add Inventory</button>
                    </form>
                </h3>
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
                <h3>
                    <form @submit.prevent="updateInventory">
                        <div>
                            <label for="editName">Name:</label>
                            <input
                                type="text"
                                id="editName"
                                v-model="editedInventory.name"
                                required
                            />
                        </div>
                        <div>
                            <label for="editDescription">Description:</label>
                            <input
                                type="text"
                                id="editDescription"
                                v-model="editedInventory.description"
                                required
                            />
                        </div>
                        <button type="submit">Update Inventory</button>
                    </form>
                </h3>
            </template>
        </modal>
    </Teleport>
</template>

<script setup lang="ts">
    import { ref, onMounted } from 'vue';
    import { axiosPrivate } from '../api/axios';
    import Modal from '../components/Modal.vue';

    const inventories = ref([]);
    const newInventory = ref({ name: '', description: '' });
    const editedInventory = ref({ id: null, name: '', description: '' }); // Renamed from editInventory to avoid conflict
    const showAddModal = ref(false);
    const showUpdateModal = ref(false);
    const currentPage = ref(1);
    const pageSize = ref(10);
    const totalInventories = ref(0);

    const getInventories = async () => {
        try {
            const response = await axiosPrivate.get('/inventories');
            const data = response.data;
            inventories.value = data.data.inventories;
            totalInventories.value = data.meta.total;
        } catch (error) {
            console.error(error);
        }
    };

    const addInventory = async () => {
        try {
            const response = await axiosPrivate.post(
                '/inventories',
                newInventory.value
            );
            const data = response.data;

            if (data.success) {
                showAddModal.value = false;
                await getInventories();
            }
        } catch (error) {
            console.error(error);
        }
    };

    const editInventory = (inventory) => {
        console.log(inventory);

        showUpdateModal.value = true;
        editedInventory.value = { ...inventory }; // Changed from editInventory to editedInventory
    };

    const updateInventory = async () => {
        try {
            const response = await axiosPrivate.put(
                `/inventories/${editedInventory.value.id}`,
                editedInventory.value
            ); // Changed from editInventory to editedInventory
            const data = response.data;

            if (data.success) {
                showUpdateModal.value = false;
                await getInventories();
            }
        } catch (error) {
            console.error(error);
        }
    };

    const deleteInventory = async (id) => {
        try {
            const response = await axiosPrivate.delete(`/inventories/${id}`);
            const data = response.data;

            if (data.success) {
                await getInventories();
            }
        } catch (error) {
            console.error(error);
        }
    };

    const handlePageChange = async (page) => {
        currentPage.value = page;
        await getInventories();
    };

    onMounted(getInventories);
</script>
