<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Builder;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_title',
        'location',
        'vehicule_id',
        'customer_id',
        'status',
        'leader_employee_id',
        'payment_status',
        'total_amount',
        'description',
        'start_at',
        'end_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
        'received_at' => 'datetime',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The available status constants.
     */
    const STATUS_RECEIVED = 'Received';
    const STATUS_IN_PROGRESS = 'In Progress...';
    const STATUS_WAITING_PRODUCTS = 'Waiting Products...';
    const STATUS_COMPLETED = 'Completed';

    const STATUSES = [
        self::STATUS_RECEIVED,
        self::STATUS_IN_PROGRESS,
        self::STATUS_WAITING_PRODUCTS,
        self::STATUS_COMPLETED,
    ];

    /**
     * The available payment status constants.
     */
    const PAYMENT_PAID = 'paid';
    const PAYMENT_UNPAID = 'unpaid';

    const PAYMENT_STATUSES = [
        self::PAYMENT_PAID,
        self::PAYMENT_UNPAID,
    ];

    /**
     * Get the customer that owns the order.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the leader employee that manages the order.
     */
    public function leaderEmployee()
    {
        return $this->belongsTo(User::class, 'leader_employee_id');
    }

    /**
     * Get the vehicle associated with the order.
     */
    public function vehicule()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Scope a query to only include orders with specific status.
     */
    public function scopeWithStatus(Builder $query,string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include received orders.
     */
    public function scopeReceived(Builder $query)
    {
        return $query->where('status', self::STATUS_RECEIVED);
    }

    /**
     * Scope a query to only include in progress orders.
     */
    public function scopeInProgress(Builder $query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    /**
     * Scope a query to only include waiting products orders.
     */
    public function scopeWaitingProducts(Builder $query)
    {
        return $query->where('status', self::STATUS_WAITING_PRODUCTS);
    }

    /**
     * Scope a query to only include completed orders.
     */
    public function scopeCompleted(Builder $query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Scope a query to only include paid orders.
     */
    public function scopePaid(Builder $query)
    {
        return $query->where('payment_status', self::PAYMENT_PAID);
    }

    /**
     * Scope a query to only include unpaid orders.
     */
    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', self::PAYMENT_UNPAID);
    }

    /**
     * Scope a query to only include orders for a specific customer.
     */
    public function scopeForCustomer(Builder $query, int $customerId)
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Scope a query to only include orders assigned to a specific employee.
     */
    public function scopeForEmployee(Builder $query, int $employeeId)
    {
        return $query->where('leader_employee_id', $employeeId);
    }

    /**
     * Check if the order is completed.
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if the order is in progress.
     */
    public function isInProgress()
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    /**
     * Check if the order is waiting for products.
     */
    public function isWaitingProducts()
    {
        return $this->status === self::STATUS_WAITING_PRODUCTS;
    }

    /**
     * Check if the order is received.
     */
    public function isReceived()
    {
        return $this->status === self::STATUS_RECEIVED;
    }

    /**
     * Check if the order is paid.
     */
    public function isPaid()
    {
        return $this->payment_status === self::PAYMENT_PAID;
    }

    /**
     * Check if the order is unpaid.
     */
    public function isUnpaid()
    {
        return $this->payment_status === self::PAYMENT_UNPAID;
    }

    /**
     * Mark order as in progress.
     */
    public function markAsInProgress()
    {
        $this->update([
            'status' => self::STATUS_IN_PROGRESS,
            'start_at' => $this->start_at ?? now(),
        ]);
    }

    /**
     * Mark order as waiting for products.
     */
    public function markAsWaitingProducts()
    {
        $this->update(['status' => self::STATUS_WAITING_PRODUCTS]);
    }

    /**
     * Mark order as completed.
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'end_at' => now(),
        ]);
    }

    /**
     * Mark order as paid.
     */
    public function markAsPaid()
    {
        $this->update(['payment_status' => self::PAYMENT_PAID]);
    }

    /**
     * Mark order as unpaid.
     */
    public function markAsUnpaid()
    {
        $this->update(['payment_status' => self::PAYMENT_UNPAID]);
    }

    /**
     * Get the duration of the order in days.
     */
    public function getDurationAttribute()
    {
        if (!$this->start_at) {
            return null;
        }

        $end = $this->end_at ?? now();
        return $this->start_at->diffInDays($end);
    }

    /**
     * format the total amount.
     */
    public function getFormattedTotalAmount()
    {
        return '$' . number_format($this->total_amount, 2);
    }
}
