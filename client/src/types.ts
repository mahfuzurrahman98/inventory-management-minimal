export type InventoryType = {
    id: number | null;
    name: string;
    description: string;
};

export type ItemType = {
    id: number | null;
    name: string;
    image: File | string | null;
    description: string;
    quantity: number;
    inventory: string | null;
    inventory_id: number | null;
};
