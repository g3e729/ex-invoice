<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoice_id',
        'product_id',
        'price',
        'quantity',
        'tax',
        'amount',
    ];

    /**
     * Get transaction record
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getProductNameAttribute()
    {
    	return "{$this->product->name}";
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
