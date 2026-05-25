<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return response()->json(['message' => 'Debes iniciar sesión para dejar una reseña'], 401);
        }

        $existing = Review::where('product_id', $request->product_id)
            ->where('customer_id', $customer->id)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Ya has escrito una reseña para este producto'], 422);
        }

        $hasPurchased = Order::where('customer_id', $customer->id)
            ->whereHas('details', function ($q) use ($request) {
                $q->where('product_id', $request->product_id);
            })
            ->exists();

        Review::create([
            'product_id' => $request->product_id,
            'customer_id' => $customer->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => !$hasPurchased,
        ]);

        $message = 'Gracias por tu reseña. Será publicada tras ser aprobada.';

        return response()->json(['message' => $message]);
    }

    public function update(Request $request, $id)
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return response()->json(['message' => 'Debes iniciar sesión'], 401);
        }

        $review = Review::where('id', $id)->where('customer_id', $customer->id)->firstOrFail();

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false,
        ]);

        return response()->json(['message' => 'Tu reseña ha sido actualizada. Será revisada nuevamente.']);
    }

    public function destroy($id)
    {
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return response()->json(['message' => 'Debes iniciar sesión'], 401);
        }

        $review = Review::where('id', $id)->where('customer_id', $customer->id)->firstOrFail();
        $review->delete();

        return response()->json(['message' => 'Tu reseña ha sido eliminada.']);
    }
}
