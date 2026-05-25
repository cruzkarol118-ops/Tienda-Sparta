<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['shop_id', 'order_code', 'name', 'phone', 'address', 'note', 'total', 'status','email', 'customer_id'];

    public function shop(){
        return $this->belongsTo(Shop::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_code', 'order_code');
    }

    use HasFactory;
}
