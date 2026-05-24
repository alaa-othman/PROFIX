<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderController extends Controller
{
    /**
     * Display a listing of orders with optional filtering.
     */
    public function index(Request $request)
    {
        $query = Order::query();

        // Filter by status
        if ($request->has('status') && in_array($request->status, Order::STATUSES)) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && in_array($request->payment_status, Order::PAYMENT_STATUSES)) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by customer
        if ($request->has('customer_id')) {
            $query->forCustomer($request->customer_id);
        }

        // Filter by employee
        if ($request->has('employee_id')) {
            $query->forEmployee($request->employee_id);
        }

        // Search by order title or description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Date range filtering
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $orders = $query->with(['customer', 'leaderEmployee', 'vehicule'])
                       ->latest()
                       ->paginate($request->get('per_page', 10));

        // Return view 
        return view('orders.index', compact('orders'));
    }

    /**
     * Store a newly created order.
     * FIXED: Returns redirect instead of JSON
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_title' => 'required|string|max:255',
            'location' => 'nullable|string|max:500',
            'vehicule_id' => 'required|exists:vehicles,id',
            'customer_id' => 'required|exists:users,id',
            'status' => 'sometimes|in:' . implode(',', Order::STATUSES),
            'leader_employee_id' => 'nullable|exists:users,id',
            'payment_status' => 'sometimes|in:' . implode(',', Order::PAYMENT_STATUSES),
            'total_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'received_at' => 'nullable|date',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
        ]);

        // Set default values if not provided
        $validated['status'] = $validated['status'] ?? Order::STATUS_RECEIVED;
        $validated['payment_status'] = $validated['payment_status'] ?? Order::PAYMENT_UNPAID;
        $validated['received_at'] = $validated['received_at'] ?? now();
        $validated['total_amount'] = $validated['total_amount'] ?? 0;

        try {
            $order = Order::create($validated);
            
            // Redirect to orders index with success message
            return redirect()->route('orders.index')
                           ->with('success', 'Order created successfully!');
        } catch (\Exception $e) {
            // Redirect back with error message and input data
            return redirect()->back()
                           ->with('error', 'Failed to create order: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the specified order.
     * FIXED: Returns view instead of JSON
     */
    public function show(int $id)
    {
        try {
            $order = Order::with(['customer', 'leaderEmployee', 'vehicule'])->findOrFail($id);
            $vehicles = Vehicle::orderBy('type')->get();
            $customers = User::where('role', 'customer')->get();
            $mechanics = User::where('role', 'mechanic')->get();
            return view('orders.show', compact('order', 'vehicles', 'customers', 'mechanics'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('orders.index')
                           ->with('error', 'Order not found');
        }
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        // Get all vehicles for the dropdown
        $vehicles = Vehicle::orderBy('type')->get();
        
        // Get all customers (users with customer role)
        $customers = User::where('role', 'customer')->get();
        
        // Get all employees for assignment
        $employees = User::where('role', 'employee')->get();

        //Get all mechanics
        $mechanics = User::where('role', 'mechanic')->get();
        
        return view('orders.create', compact('vehicles', 'customers', 'employees', 'mechanics'));
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(int $id)
    {
        try {
            $order = Order::with(['customer', 'leaderEmployee', 'vehicule'])->findOrFail($id);
            
            // Get all vehicles for the dropdown
            $vehicles = Vehicle::orderBy('type')->get();
            
            // Get all customers
            $customers = User::where('role', 'customer')->get();
            // Get all employees for assignment
            $employees = User::where('role', 'employee')->get();
            // Get all mechanics
            $mechanics = User::where('role', 'mechanic')->get();

            return view('orders.edit', compact('order', 'vehicles', 'customers', 'employees', 'mechanics'));
        } catch (\Exception $e) {
            return redirect()->route('orders.index')->with('error', 'Order not found.');
        }
    }

    /**
     * Update the specified order.
     * FIXED: Returns redirect instead of JSON
     */
    public function update(Request $request, int $id)
    {
        try {
            $order = Order::findOrFail($id);

            $validated = $request->validate([
                'order_title' => 'sometimes|string|max:255',
                'location' => 'nullable|string|max:500',
                'vehicule_id' => 'sometimes|exists:vehicles,id',
                'customer_id' => 'sometimes|exists:users,id',
                'status' => 'sometimes|in:' . implode(',', Order::STATUSES),
                'leader_employee_id' => 'nullable|exists:users,id',
                'payment_status' => 'sometimes|in:' . implode(',', Order::PAYMENT_STATUSES),
                'total_amount' => 'nullable|numeric|min:0',
                'description' => 'nullable|string',
                'received_at' => 'nullable|date',
                'start_at' => 'nullable|date',
                'end_at' => 'nullable|date|after_or_equal:start_at',
            ]);

            $order->update($validated);

            return redirect()->route('orders.index')
                           ->with('success', 'Order updated successfully!');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()
                           ->with('error', 'Order not found')
                           ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Failed to update order: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Remove the specified order.
     * FIXED: Returns redirect instead of JSON
     */
    public function destroy(int $id)
    {
        try {
            $order = Order::findOrFail($id);

            // Check if order can be deleted (e.g., not completed or has dependencies)
            if ($order->isCompleted()) {
                return redirect()->route('orders.index')
                               ->with('error', 'Cannot delete completed orders');
            }

            $order->delete();

            return redirect()->route('orders.index')
                           ->with('success', 'Order deleted successfully');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('orders.index')
                           ->with('error', 'Order not found');
        } catch (\Exception $e) {
            return redirect()->route('orders.index')
                           ->with('error', 'Failed to delete order: ' . $e->getMessage());
        }
    }

    /**
     * Change order status.
     * Can keep as JSON if used via AJAX, or change to redirect
     */
    public function changeStatus(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', Order::STATUSES)
        ]);

        try {
            $order = Order::findOrFail($id);
            
            // Use the built-in methods for status changes
            switch ($validated['status']) {
                case Order::STATUS_IN_PROGRESS:
                    $order->markAsInProgress();
                    break;
                case Order::STATUS_WAITING_PRODUCTS:
                    $order->markAsWaitingProducts();
                    break;
                case Order::STATUS_COMPLETED:
                    $order->markAsCompleted();
                    break;
                case Order::STATUS_RECEIVED:
                    $order->update(['status' => Order::STATUS_RECEIVED]);
                    break;
                default:
                    $order->update(['status' => $validated['status']]);
            }

            // If request expects JSON (AJAX), return JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order status updated successfully',
                    'data' => $order
                ]);
            }

            // Otherwise redirect back
            return redirect()->back()
                           ->with('success', 'Order status updated successfully!');
        } catch (ModelNotFoundException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }
            return redirect()->back()->with('error', 'Order not found');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update order status',
                    'error' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Failed to update order status');
        }
    }

    /**
     * Assign an employee to the order.
     * Can keep as JSON if used via AJAX, or change to redirect
     */
    public function assignEmployee(Request $request, int $id)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:users,id',
            'role' => 'nullable|string|in:leader,team_member'
        ]);

        try {
            $order = Order::findOrFail($id);
            $employee = User::findOrFail($validated['employee_id']);

            $order->leader_employee_id = $validated['employee_id'];
            $order->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Employee assigned to order successfully',
                    'data' => $order
                ]);
            }

            return redirect()->back()
                           ->with('success', 'Employee assigned successfully!');
        } catch (ModelNotFoundException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order or employee not found'
                ], 404);
            }
            return redirect()->back()->with('error', 'Order or employee not found');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to assign employee',
                    'error' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Failed to assign employee');
        }
    }

    /**
     * Update order total amount/price.
     * Can keep as JSON if used via AJAX, or change to redirect
     */
    public function updatePrice(Request $request, int $id)
    {
        $validated = $request->validate([
            'total_amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string|max:500'
        ]);

        try {
            $order = Order::findOrFail($id);
            $order->total_amount = $validated['total_amount'];
            $order->save();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order price updated successfully',
                    'data' => $order
                ]);
            }

            return redirect()->back()
                           ->with('success', 'Price updated successfully!');
        } catch (ModelNotFoundException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }
            return redirect()->back()->with('error', 'Order not found');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update order price',
                    'error' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Failed to update price');
        }
    }

    /**
     * Get orders statistics.
     * Keep as JSON for API/stats page
     */
    public function statistics(Request $request)
    {
        try {
            $stats = [
                'total_orders' => Order::count(),
                'by_status' => [
                    'received' => Order::received()->count(),
                    'in_progress' => Order::inProgress()->count(),
                    'waiting_products' => Order::waitingProducts()->count(),
                    'completed' => Order::completed()->count(),
                ],
                'by_payment_status' => [
                    'paid' => Order::paid()->count(),
                    'unpaid' => Order::unpaid()->count(),
                ],
                'total_revenue' => Order::paid()->sum('total_amount'),
                'average_order_value' => Order::avg('total_amount'),
                'recent_orders' => Order::with(['customer', 'leaderEmployee'])
                                      ->latest()
                                      ->take(10)
                                      ->get()
            ];

            // If request expects HTML view
            if (!$request->expectsJson()) {
                return view('orders.statistics', compact('stats'));
            }

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch statistics',
                    'error' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Failed to fetch statistics');
        }
    }

    /**
     * Bulk update order statuses.
     * Keep as JSON for API
     */
    public function bulkStatusUpdate(Request $request)
    {
        $validated = $request->validate([
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'exists:orders,id',
            'status' => 'required|in:' . implode(',', Order::STATUSES)
        ]);

        try {
            DB::beginTransaction();
            
            $updatedCount = 0;
            $orders = Order::whereIn('id', $validated['order_ids'])->get();
            
            foreach ($orders as $order) {
                switch ($validated['status']) {
                    case Order::STATUS_IN_PROGRESS:
                        $order->markAsInProgress();
                        break;
                    case Order::STATUS_COMPLETED:
                        $order->markAsCompleted();
                        break;
                    default:
                        $order->status = $validated['status'];
                        $order->save();
                }
                $updatedCount++;
            }
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$updatedCount} orders updated successfully",
                'data' => [
                    'updated_count' => $updatedCount,
                    'new_status' => $validated['status']
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update orders',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}