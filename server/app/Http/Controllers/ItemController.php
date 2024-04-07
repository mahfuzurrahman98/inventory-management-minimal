Certainly! Here's the code with proper comments:

```php
<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ItemController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        try {
            // Get the authenticated user
            $user = auth()->user();

            // Query to fetch items related to the user
            $itemsQry = Item::whereHas('inventory', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });

            // Apply filters if any
            if ($request->inventoryId) {
                $itemsQry->where('inventory_id', $request->inventoryId);
            }

            if ($request->name) {
                $itemsQry->where('name', 'like', '%' . $request->name . '%');
            }

            // Count total items
            $total = $itemsQry->count();

            // Paginate items
            $items = $itemsQry->paginate(10);

            // Return JSON response
            return response()->json([
                'success' => true,
                'message' => 'Items retrieved successfully',
                'data' => [
                    'items' => ItemResource::collection($items->items()),
                    'total' => $total
                ]
            ]);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'inventory_id' => 'required|integer|exists:inventories,id,user_id,' . $request->user()->id,
                'name' => 'required|unique:items,name,null,null,inventory_id,' . $request->inventory_id,
                'description' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg|max:1024',
                'quantity' => 'required|integer|min:1',
            ]);

            // Return validation errors
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()
                ], 422);
            }

            $imageName = null;

            // Store the item image
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->move(public_path('images'), $imageName);
            }

            // Create a new item
            $item = new Item();
            $item->name = $request->name;
            $item->description = $request->description;
            $item->image = $imageName;
            $item->quantity = $request->quantity;
            $item->inventory_id = $request->inventory_id;
            $item->save();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Item created successfully',
                'data' => [
                    'item' => $item,
                ]
            ], 201);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item) {
        try {
            // Check if the item belongs to the authenticated user
            if ($item->inventory->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Return JSON response with the item details
            return response()->json([
                'success' => true,
                'message' => 'Item retrieved successfully',
                'data' => [
                    'item' => $item
                ]
            ]);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item) {
        try {
            // Check if the item belongs to the authenticated user
            if ($item->inventory->user_id !== auth()->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Validate the request data
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:items,name,' . $item->id . ',id,inventory_id,' . $item->inventory_id,
                'description' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg|max:1024',
                'quantity' => 'required|integer|min:1',
            ]);

            // Return validation errors
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()
                ], 422);
            }

            // Update the item fields
            $item->name = $request->name;
            $item->description = $request->description;
            $item->quantity = $request->quantity;

            // Update the item image if provided
            if ($request->has('image')) {
                $imageName = $item->image;

                // Store the new item image and delete the old one
                if ($request->hasFile('image')) {
                    if ($imageName) {
                        $imagePath = public_path('/images/' . $imageName);
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }

                    $image = $request->file('image');
                    $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->move(public_path('images'), $imageName);
                }

                $item->image = $imageName;
            }

            // Save the updated item
            $item->save();

            // Return success response
            return response()->json([
                'success' => true,
                'data' => [
                    'item' => $item,
                ]
            ]);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item) {
        try {
            // Check if the item belongs to the authenticated user
            if ($item->inventory->user_id !== auth()->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Delete the item
            $item->delete();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Item deleted successfully'
            ]);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
