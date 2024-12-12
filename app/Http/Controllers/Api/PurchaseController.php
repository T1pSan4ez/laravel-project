<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\PurchaseMailer;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\SessionSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'session_id' => 'required|integer|exists:sessions,id',
            'purchase_code' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'email' => 'required|email|max:255',
            'items' => 'required|array',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.slot_id' => 'nullable|integer',
            'items.*.product_id' => 'nullable|integer',
        ]);

        $purchase = Purchase::create([
            'purchase_code' => $validatedData['purchase_code'],
            'user_id' => $validatedData['user_id'] ?? null,
        ]);

        $purchaseItems = [];
        foreach ($validatedData['items'] as $item) {
            $sessionSlot = null;

            if (!empty($item['slot_id'])) {
                $sessionSlot = SessionSlot::where('slot_id', $item['slot_id'])
                    ->where('session_id', $validatedData['session_id'])
                    ->first();

                if ($sessionSlot) {
                    $sessionSlot->update(['status' => 'paid']);
                }
            }

            $purchaseItems[] = [
                'purchase_id' => $purchase->id,
                'slot_id' => $sessionSlot ? $sessionSlot->id : null,
                'product_id' => $item['product_id'] ?? null,
                'item_name' => $item['item_name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        PurchaseItem::insert($purchaseItems);

        Mail::to($validatedData['email'])->queue(new PurchaseMailer($purchase->load('items')));

        return response()->json([
            'message' => 'Purchase successful.',
            'purchase' => $purchase->load('items'),
        ], 201);
    }




}
