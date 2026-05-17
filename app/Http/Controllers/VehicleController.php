<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */ 
    public function index()
    {
        // Return All Vehicles with their owners in a view
        $vehicles = Vehicle::with('owner')->get();
        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|string|max:50',
                'color' => 'required|string|max:50',
                'model' => 'required|string|max:10',
                'user_owned_id' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $vehicle = Vehicle::create($request->all());

            return response()->json([
                'success' => true,
                'data' => $vehicle->load('owner'),
                'message' => 'Vehicle created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create vehicle',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function showUpdateForm()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,int $id)
    {
        try {
            $vehicle = Vehicle::find($id);
            
            if (!$vehicle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'type' => 'sometimes|required|string|max:50',
                'color' => 'sometimes|required|string|max:50',
                'model' => 'sometimes|required|string|max:10',
                'user_owned_id' => 'sometimes|required|exists:users,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $vehicle->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $vehicle->load('owner'),
                'message' => 'Vehicle updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update vehicle',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
        public function destroy(int $id)
    {
        try {
            $vehicle = Vehicle::find($id);
            
            if (!$vehicle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle not found'
                ], 404);
            }

            $vehicle->delete();

            return response()->json([
                'success' => true,
                'message' => 'Vehicle deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete vehicle',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Get Vehicles By Type.
     */
    public function getByType(string $type)
    {
        try {
            $vehicles = Vehicle::where('type', $type)->with('owner')->get();

            if ($vehicles->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'No vehicles found with type: ' . $type
                ], 200);
            }

            return response()->json([
                'success' => true,
                'data' => $vehicles,
                'message' => 'Vehicles retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve vehicles',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Get vehicles by owner
     */
    public function getByOwner(int $userId) // id of owner
    {
        try {
            $user = User::find($userId);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $vehicles = Vehicle::with('owner')
                ->where('user_owned_id', $userId)
                ->get();
            
            if ($vehicles->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'owner' => $user->first_name . ' ' . $user->last_name,
                    'message' => 'User with id ' . $userId . ' has no vehicles'
                ], 200);
            }

            return response()->json([
                'success' => true,
                'data' => $vehicles,
                'owner' => $user->first_name . ' ' . $user->last_name,
                'message' => 'Vehicles retrieved successfully by owner'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve vehicles',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
