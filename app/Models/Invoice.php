<?php

namespace App\Models;

use App\Models\InvoiceItem;
use App\Models\PaymentLine;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'invoice_date',
        'due_date',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
        'notes',
        'invoice_number',
        'invoice_date',
        'due_date',
        'amount',
    ];
    
    /**
     * Get the items of this transaction
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
    
    /**
     * Get the payment lines of this transaction
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payment_lines()
    {
        return $this->hasMany(PaymentLine::class);
    }
}
