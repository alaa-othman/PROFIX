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
        $vehicles = Vehicle::with('owner')->latest()->paginate(10);
        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new vehicle.
     */
    public function createForm()
    {
        $users = User::all();
        return view('vehicles.create', compact('users'));
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
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'model' => 'required|string|max:10',
            'user_owned_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Vehicle::create($request->all());
            return redirect()->route('vehicles.index')
                ->with('success', 'Vehicle created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create vehicle: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $vehicle = Vehicle::with('owner')->findOrFail($id);
            return view('vehicles.show', compact('vehicle'));
        } catch (\Exception $e) {
            return redirect()->route('vehicles.index')
                ->with('error', 'Vehicle not found!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $vehicle = Vehicle::findOrFail($id);
            $users = User::all();
            return view('vehicles.edit', compact('vehicle', 'users'));
        } catch (\Exception $e) {
            return redirect()->route('vehicles.index')
                ->with('error', 'Vehicle not found!');
        }
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
    public function update(Request $request, int $id)
    {
        try {
            $vehicle = Vehicle::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'type' => 'required|string|max:50',
                'color' => 'required|string|max:50',
                'model' => 'required|string|max:10',
                'user_owned_id' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $vehicle->update($request->all());
            return redirect()->route('vehicles.index')
                ->with('success', 'Vehicle updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update vehicle: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $vehicle = Vehicle::findOrFail($id);
            $vehicle->delete();
            return redirect()->route('vehicles.index')
                ->with('success', 'Vehicle deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('vehicles.index')
                ->with('error', 'Failed to delete vehicle: ' . $e->getMessage());
        }
    }

    /**
     * Display vehicles by type.
     */
    public function showByType(string $type)
    {
        $vehicles = Vehicle::with('owner')
            ->where('type', $type)
            ->paginate(10);
        
        return view('vehicles.by-type', compact('vehicles', 'type'));
    }
    /**
     * Get vehicles by owner
     */
     public function showByOwner(int $userId)
    {
        try {
            $user = User::findOrFail($userId);
            $vehicles = Vehicle::with('owner')
                ->where('user_owned_id', $userId)
                ->paginate(10);
            
            return view('vehicles.by-owner', compact('vehicles', 'user'));
        } catch (\Exception $e) {
            return redirect()->route('vehicles.index')
                ->with('error', 'User not found!');
        }
    }
}
