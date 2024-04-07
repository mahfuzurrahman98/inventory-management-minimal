<template>
    <Layout>
        <div class="max-w-3xl w-full">
            <div class="flex justify-between">
                <h3 class="text-2xl">Create Item</h3>
                <router-link to="/items" class="text-md underline">
                    Back to Items
                </router-link>
            </div>
            <hr class="mt-3 mb-10" />
            <form
                v-if="!componentLoading"
                @submit.prevent="createItem"
                class="flex flex-col gap-y-3"
            >
                <div>
                    <label for="name" class="text-base font-medium">Name</label>
                    <input
                        id="name"
                        v-model="item.name"
                        type="text"
                        required
                        class="mt-1 px-3 py-1 w-full border rounded-md focus:outline-blue-700"
                    />
                </div>
                <div v-if="inventories.length">
                    <label for="inventory" class="text-base font-medium"
                        >Inventory</label
                    >
                    <select
                        id="inventory"
                        v-model="item.inventory_id"
                        required
                        class="mt-1 px-3 py-1 w-full border rounded-md focus:outline-blue-700"
                    >
                        <option :value="null" selected>Select Inventory</option>
                        <option
                            v-for="inventory in inventories"
                            :key="inventory.id!"
                            :value="inventory.id"
                        >
                            {{ inventory.name }}
                        </option>
                    </select>
                </div>
                <div v-else>
                    <p
                        to="/inventories/create"
                        class="bg-blue-700 rounded text-white text-sm px-3 py-1"
                        @click="showAddInventoryModal = true"
                    >
                        Create an inventory
                    </p>
                </div>

                <div>
                    <label for="description" class="text-base font-medium"
                        >Description (max 255 characters)
                    </label>
                    <textarea
                        id="description"
                        v-model="item.description"
                        maxlength="255"
                        required
                        class="mt-1 px-3 py-1 w-full border rounded-md focus:outline-blue-700"
                    ></textarea>
                </div>
                <div>
                    <label for="quantity" class="text-base font-medium"
                        >Quantity</label
                    >
                    <input
                        id="quantity"
                        v-model.number="item.quantity"
                        type="number"
                        required
                        class="mt-1 px-3 py-1 w-full border rounded-md focus:outline-blue-700"
                    />
                </div>
                <div>
                    <label for="image" class="text-base font-medium"
                        >Image</label
                    >
                    <img
                        v-if="
                            selectedImageSrc &&
                            typeof selectedImageSrc === 'string'
                        "
                        :src="selectedImageSrc"
                        alt="Item Image"
                        class="border-2 border-gray-200 mt-1 w-36 h-24 object-cover rounded-md"
                    />
                    <input
                        id="image"
                        @change="onFileChange"
                        type="file"
                        class="mt-1 py-1 w-full border rounded-r-md focus:outline-blue-700"
                    />
                </div>
                <div>
                    <button
                        type="submit"
                        :class="`bg-blue-800 text-white px-3 py-2 rounded-md text-md hover:bg-blue-700 active:bg-blue-800 ${
                            createBtnLoading || inventories.length === 0
                                ? 'opacity-50 cursor-not-allowed'
                                : ''
                        }`"
                        :disabled="createBtnLoading || inventories.length === 0"
                    >
                        {{ createBtnLoading ? 'Creating...' : 'Create' }}
                    </button>
                </div>
            </form>
            <div
                v-else
                className="flex flex-col items-center justify-center h-full"
            >
                <div
                    className="w-24 h-24 border-8 border-dashed rounded-full animate-spin border-blue-800"
                ></div>
            </div>
        </div>

        <!-- Add Inventory Modal -->
        <Teleport to="body">
            <modal
                :show="showAddInventoryModal"
                @close="showAddInventoryModal = false"
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
                                    addInventoryBtnLoading
                                        ? 'opacity-50 cursor-not-allowed'
                                        : ''
                                }`"
                                :disabled="addInventoryBtnLoading"
                            >
                                {{
                                    addInventoryBtnLoading
                                        ? 'Loading...'
                                        : 'Save'
                                }}
                            </button>
                        </div>
                    </form>
                </template>
            </modal>
        </Teleport>
    </Layout>
</template>

<script setup lang="ts">
    import { onMounted, ref, computed } from 'vue';
    import { useRouter } from 'vue-router';
    import { POSITION, useToast } from 'vue-toastification';
    import { axiosPrivate } from '../api/axios';
    import Layout from '../components/Layout.vue';
    import { InventoryType, ItemType } from '../types';
    import formatError from '../helpers/formatError';
    import Modal from '../components/Modal.vue';

    const router = useRouter();

    const inventories = ref<InventoryType[]>([]);
    const newInventory = ref({ name: '', description: '' });

    const item = ref<ItemType>({
        id: null,
        name: '',
        image: '',
        description: '',
        quantity: 0,
        inventory: '',
        inventory_id: null,
    });

    const componentLoading = ref(true);
    const createBtnLoading = ref(false);
    const addInventoryBtnLoading = ref(false);
    const showAddInventoryModal = ref(false);

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

    const selectedImageSrc = computed(() => {
        return item.value.image && typeof item.value.image === 'object'
            ? URL.createObjectURL(item.value.image)
            : '';
    });

    const onFileChange = (e: Event) => {
        const file = (e.target as HTMLInputElement).files![0];
        console.log(file);
        item.value.image = file;
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

    const addInventory = async () => {
        try {
            addInventoryBtnLoading.value = true;

            const response = await axiosPrivate.post(
                '/inventories',
                newInventory.value
            );
            const data = response.data;

            if (data.success) {
                showAddInventoryModal.value = false;
                await getInventories();
                toast.success('Inventory added successfully', toastOptions);
            }
        } catch (err: any) {
            let errorMessage = formatError(err);

            // alert(errorMessage);
            toast.error(errorMessage, toastOptions);
        } finally {
            addInventoryBtnLoading.value = false;
        }
    };

    const createItem = async () => {
        try {
            if (!item.value.inventory_id) {
                toast.error('Please select an inventory', toastOptions);
                return;
            }
            const formData = new FormData();
            formData.append('name', item.value.name);
            formData.append('description', item.value.description);
            formData.append('quantity', item.value.quantity.toString());
            // send image only if it is present
            if (item.value.image) {
                formData.append('image', item.value.image!);
            }

            formData.append(
                'inventory_id',
                item.value.inventory_id!.toString()
            );

            console.log(formData);
            // return;
            await axiosPrivate.post('/items', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
            toast.success('Item created successfully', toastOptions);
            router.push('/items');
        } catch (error) {
            console.error(error);
            let errorMessage = formatError(error);
            toast.error(errorMessage, toastOptions);
        } finally {
            createBtnLoading.value = false;
        }
    };

    onMounted(getInventories);
</script>
