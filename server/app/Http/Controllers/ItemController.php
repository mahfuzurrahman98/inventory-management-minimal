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
            $user = auth()->user();

            $itemsQry = Item::whereHas('inventory', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });

            if ($request->inventoryId) {
                $itemsQry->where('inventory_id', $request->inventoryId);
            }

            $items = $itemsQry->paginate(1000);
            $total = $items->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'items' => ItemResource::collection($items->items()),
                    'total' => $total
                ]
            ]);
        } catch (\Exception $e) {
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
            // dd($request->all(   ));
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
                $imagePath = $image->storeAs('public/images', $imageName);
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
                'data' => [
                    'item' => $item,
                ]
            ], 201);
        } catch (\Exception $e) {
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
            // chechk the $item belongs to the authenticated user
            if ($item->inventory->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'item' => $item
                ]
            ]);
        } catch (\Exception $e) {
            // Handle any other exceptions
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
            // chechk the $item belongs to the authenticated user
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
                    'message' => $validator->errors()->toArray()
                ], 422);
            }



            // Update the item
            $item->name = $request->name;
            $item->description = $request->description;
            $item->quantity = $request->quantity;

            if ($request->has('image')) {
                $imageName = $item->image;

                // Store the item image and delete the old image
                if ($request->hasFile('image')) {
                    // delete 
                    if ($imageName) {
                        $imagePath = storage_path('app/public/images/' . $imageName);
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                    }

                    $image = $request->file('image');
                    $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
                    $imagePath = $image->storeAs('public/images', $imageName);
                }
                $item->image = $imageName;
            }
            $item->save();

            // Return success response
            return response()->json([
                'success' => true,
                'data' => [
                    'item' => $item,
                ]
            ]);
        } catch (\Exception $e) {
            // Handle any other exceptions
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
            // chechk the $item belongs to the authenticated user
            if ($item->inventory->user_id !== auth()->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            $item->delete();
            return response()->json([
                'success' => true,
                'message' => 'Item deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
