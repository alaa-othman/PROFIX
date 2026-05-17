<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'located_at',
        'buy_price',
        'sell_price',
        'buy_date',
        'used_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'buy_date' => 'date',
        'used_at' => 'date',
        'buy_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
    ];

    /**
     * Get the profit margin for the product.
     */
    public function getProfitMarginAttribute(): float
    {
        return $this->sell_price - $this->buy_price;
    }

    /**
     * Get the profit percentage.
     */
    public function getProfitPercentageAttribute(): float
    {
        if ($this->buy_price == 0) {
            return 0;
        }
        return (($this->sell_price - $this->buy_price) / $this->buy_price) * 100;
    }

}
