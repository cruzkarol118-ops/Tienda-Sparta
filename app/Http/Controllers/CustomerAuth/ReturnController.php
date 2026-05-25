<?php

namespace App\Http\Controllers\CustomerAuth;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use App\Models\Order;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReturnController extends Controller
{
    public function showForm()
    {
        $data = [
            'shop' => Shop::first(),
            'title' => 'Solicitar Devolución / Garantía'
        ];
        return view('customer.returns.form', $data);
    }

    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_code' => 'required|string|exists:orders,order_code',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'reason' => 'required|string|max:1000',
            'description' => 'nullable|string|max:2000',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $order = Order::where('order_code', $request->order_code)->first();

        if (!$order) {
            return back()->withErrors(['order_code' => 'No se encontró una orden con ese código.'])->withInput();
        }

        $imagePath = null;
        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('returns', 'public');
        }

        ReturnRequest::create([
            'customer_id' => Auth::guard('customer')->check() ? Auth::guard('customer')->id() : null,
            'order_code' => $request->order_code,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'reason' => $request->reason,
            'description' => $request->description,
            'product_image' => $imagePath,
            'status' => 'pending',
        ]);

        return redirect()->route('clientHome')->with('success', 'Tu solicitud de devolución/garantía ha sido enviada. Te contactaremos pronto.');
    }

    public function myReturns()
    {
        $customerId = Auth::guard('customer')->id();

        if (!$customerId) {
            return redirect()->route('customer.login');
        }

        $returns = ReturnRequest::where('customer_id', $customerId)
                    ->orderByDesc('id')
                    ->get();

        $data = [
            'shop' => Shop::first(),
            'returns' => $returns,
            'title' => 'Mis Solicitudes'
        ];

        return view('customer.returns.my-returns', $data);
    }
}