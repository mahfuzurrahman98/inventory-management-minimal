<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        try {
            // Query inventories belonging to the authenticated user
            $inventoriesQry = Inventory::where('user_id', $request->user()->id);

            // Get the total count of inventories
            $total = $inventoriesQry->count();

            // Apply search filter if name parameter is provided
            if ($request->name) {
                $inventoriesQry->where('name', 'like', '%' . $request->name . '%');
            }

            // Paginate the query results
            $inventories = $inventoriesQry
                ->paginate(5);

            // Return JSON response with paginated inventories and total count
            return response()->json([
                'success' => true,
                'data' => [
                    'inventories' => $inventories->items(),
                    'total' => $total
                ]
            ], 200);
        } catch (\Exception $e) {
            // Handle exceptions and return error response
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
                'name' => 'required|unique:inventories,name,NULL,NULL,user_id,' . $request->user()->id,
                'description' => 'required'
            ]);

            // Return validation errors if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->messages()
                ], 422);
            }

            // Create a new inventory
            $inventory = Inventory::create([
                'name' => $request->name,
                'description' => $request->description,
                'user_id' => $request->user()->id
            ]);

            // Return success response with the created inventory
            return response()->json([
                'success' => true,
                'data' => [
                    'inventory' => $inventory
                ]
            ], 201);
        } catch (\Exception $e) {
            // Handle exceptions and return error response
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $id) {
        try {
            // Find the inventory belonging to the authenticated user by ID
            $inventory = $request->user()->inventories()->find($id);

            // Return error response if inventory is not found
            if (!$inventory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inventory not found'
                ], 404);
            }

            // Return success response with the found inventory
            return response()->json([
                'success' => true,
                'data' => [
                    'inventory' => $inventory
                ]
            ], 200);
        } catch (\Exception $e) {
            // Handle exceptions and return error response
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id) {
        try {
            // Find the inventory to update belonging to the authenticated user
            $inventory = $request->user()->inventories()->find($id);

            // Return error response if inventory is not found
            if (!$inventory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inventory not found'
                ], 404);
            }

            // Validate the request data
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:inventories,name,' . $inventory->id . ',id,user_id,' . $request->user()->id,
                'description' => 'required'
            ]);

            // Return validation errors if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->messages()
                ], 422);
            }

            // Update the inventory with the validated data
            $inventory->update([
                'name' => $request->name,
                'description' => $request->description
            ]);

            // Return success response with the updated inventory
            return response()->json([
                'success' => true,
                'data' => [
                    'inventory' => $inventory
                ]
            ], 200);
        } catch (\Exception $e) {
            // Handle exceptions and return error response
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id) {
        try {
            // Find the inventory to delete belonging to the authenticated user
            $inventory = $request->user()->inventories()->find($id);

            // Return error response if inventory is not found
            if (!$inventory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inventory not found'
                ], 404);
            }

            // Delete the inventory
            $inventory->delete();

            // Return success response
            return response()->json([
                'success' => true
            ], 200);
        } catch (\Exception $e) {
            // Handle exceptions and return error response
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
