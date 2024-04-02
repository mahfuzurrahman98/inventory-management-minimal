<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
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
                    'message' => $validator->errors()->toArray()
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item) {
        //
    }
}
