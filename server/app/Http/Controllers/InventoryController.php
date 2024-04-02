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
            $inventories = $request->user()->inventories;
            return response()->json([
                'success' => true,
                'data' => [
                    'inventories' => $inventories
                ]
            ], 200);
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
            // validate the request data
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required'
            ]);

            // return validation errors
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->messages()->toArray()
                ], 422);
            }

            // create a new inventory
            $inventory = new Inventory();
            $inventory->name = $request->name;
            $inventory->description = $request->description;
            $inventory->user_id = $request->user()->id;
            $inventory->save();

            // return success response
            return response()->json([
                'success' => true,
                'data' => [
                    'inventory' => $inventory
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
    public function show(Request $request, int $id) {
        try {
            $inventory = $request->user()->inventories()->find($id);

            if (!$inventory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inventory not found'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'inventory' => $inventory
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory $inventory) {
        try {
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory $inventory) {
        try {
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory) {
        try {
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
