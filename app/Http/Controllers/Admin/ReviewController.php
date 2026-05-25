<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['product', 'customer'])
            ->orderByRaw('is_approved ASC')
            ->latest()
            ->paginate(20);

        $data = [
            'title' => 'Reseñas',
            'reviews' => $reviews,
        ];

        return view('admin.review.index', $data);
    }

    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['is_approved' => true]);

        return redirect()->back()->with('success', 'Reseña aprobada correctamente');
    }

    public function reject($id)
    {
        $review = Review::findOrFail($id);
        $review->update(['is_approved' => false]);

        return redirect()->back()->with('success', 'Reseña rechazada correctamente');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->back()->with('success', 'Reseña eliminada correctamente');
    }
}
