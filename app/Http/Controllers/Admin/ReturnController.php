<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index()
    {
        $returns = ReturnRequest::with('customer')
                    ->orderByDesc('id')
                    ->get();

        $data = [
            'title' => 'Devoluciones / Garantías',
            'returns' => $returns,
        ];

        return view('admin.returns.index', $data);
    }

    public function detail($id)
    {
        $return = ReturnRequest::with('customer', 'order')->findOrFail($id);

        $data = [
            'title' => 'Detalle de Solicitud #' . $return->id,
            'return' => $return,
        ];

        return view('admin.returns.detail', $data);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed,cancelled',
            'admin_note' => 'nullable|string|max:2000',
        ]);

        $return = ReturnRequest::findOrFail($id);

        if ($request->status === 'rejected' && empty($request->admin_note)) {
            return back()->withErrors(['admin_note' => 'Debes proporcionar una razón al rechazar la solicitud.'])->withInput();
        }

        $return->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
        ]);

        return redirect()->route('admin.returns.detail', $id)->with('success', 'Estado actualizado correctamente.');
    }
}