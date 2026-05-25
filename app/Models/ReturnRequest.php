<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'customer_id',
        'order_code',
        'customer_name',
        'customer_email',
        'customer_phone',
        'reason',
        'description',
        'product_image',
        'status',
        'admin_note',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_code', 'order_code');
    }
}