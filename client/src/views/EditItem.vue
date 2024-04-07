<template>
    <Layout>
        <div class="max-w-3xl w-full">
            <div class="flex justify-between">
                <h3 class="text-2xl">Edit Item</h3>
                <router-link to="/items" class="text-md underline">
                    Back to Items
                </router-link>
            </div>
            <hr class="mt-3 mb-10" />
            <form
                v-if="!componentLoading"
                @submit.prevent="editItem"
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
                <div
                    v-else
                    className="flex flex-col items-center justify-center h-full"
                >
                    <div
                        className="w-24 h-24 border-8 border-dashed rounded-full animate-spin border-blue-800"
                    ></div>
                </div>

                <div>
                    <label for="description" class="text-base font-medium">
                        Description (max 255 characters)
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
                            selectedImage && typeof selectedImage === 'object'
                        "
                        :src="selectedImageSrc"
                        alt="Item Image"
                        class="border-2 border-gray-200 mt-1 w-36 h-24 object-cover rounded-md"
                    />
                    <img
                        v-else-if="item.image && typeof item.image === 'string'"
                        :src="item.image"
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
                            editBtnLoading
                                ? 'opacity-50 cursor-not-allowed'
                                : ''
                        }`"
                        :disabled="editBtnLoading"
                    >
                        {{ editBtnLoading ? 'Updating...' : 'Update' }}
                    </button>
                </div>
            </form>
        </div>
    </Layout>
</template>

<script setup lang="ts">
    import { computed, onMounted, ref } from 'vue';
    import { useRouter } from 'vue-router';
    import { useRoute } from 'vue-router';
    import { POSITION, useToast } from 'vue-toastification';
    import { axiosPrivate } from '../api/axios';
    import Layout from '../components/Layout.vue';
    import { InventoryType, ItemType } from '../types';
    import formatError from '../helpers/formatError';

    const router = useRouter();
    const route = useRoute();

    const componentLoading = ref(true);

    const inventories = ref<InventoryType[]>([]);
    const item = ref<ItemType>({
        id: null,
        name: '',
        image: '',
        description: '',
        quantity: 0,
        inventory: '',
        inventory_id: null,
    });
    const selectedImage = ref<File | string | null>('');

    const editBtnLoading = ref(false);
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

    const onFileChange = (e: Event) => {
        const file = (e.target as HTMLInputElement).files![0];
        console.log(file);
        selectedImage.value = file;
    };

    const getInventories = async () => {
        try {
            const response = await axiosPrivate.get(`/inventories`);
            const data = response.data;
            inventories.value = data.data.inventories;
        } catch (error) {
            console.error(error);
        }
    };

    const getItem = async () => {
        try {
            const response = await axiosPrivate.get(
                `/items/${route.params.id}`
            );
            const data = response.data;
            item.value = data.data.item;
        } catch (error) {
            console.error(error);
        }
    };

    const selectedImageSrc = computed(() => {
        return selectedImage.value && typeof selectedImage.value === 'object'
            ? URL.createObjectURL(selectedImage.value)
            : '';
    });

    const editItem = async () => {
        try {
            editBtnLoading.value = true;
            if (!item.value.inventory_id) {
                toast.error('Please select an inventory', toastOptions);
                return;
            }
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('name', item.value.name);
            formData.append('description', item.value.description);
            formData.append('quantity', item.value.quantity.toString());
            // send image only if it is present
            if (selectedImage.value) {
                formData.append('image', selectedImage.value);
            }

            formData.append(
                'inventory_id',
                item.value.inventory_id!.toString()
            );

            console.log(formData);
            // return;
            await axiosPrivate.post(`/items/${route.params.id}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                },
            });
            toast.success('Item updated successfully', toastOptions);
            router.push('/items');
        } catch (error) {
            console.error(error);
            let errorMessage = formatError(error);
            toast.error(errorMessage, toastOptions);
        } finally {
            editBtnLoading.value = false;
        }
    };

    onMounted(async () => {
        try {
            componentLoading.value = true;
            await getInventories();
            await getItem();
        } catch (error) {
        } finally {
            componentLoading.value = false;
        }
    });
</script>
