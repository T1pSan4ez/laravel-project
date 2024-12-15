<?php

namespace App\Repositories;

use App\Interfaces\PurchaseRepositoryInterface;
use App\Mail\PurchaseMailer;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\SessionSlot;
use Illuminate\Support\Facades\Mail;

class PurchaseRepository implements PurchaseRepositoryInterface
{
    public function createPurchase(array $purchaseData, array $itemsData): Purchase
    {
        $purchase = Purchase::create([
            'purchase_code' => $purchaseData['purchase_code'],
            'user_id' => $purchaseData['user_id'] ?? null,
        ]);

        $purchaseItems = [];

        foreach ($itemsData as $item) {
            $sessionSlot = null;

            if (!empty($item['slot_id'])) {
                $sessionSlot = SessionSlot::where('slot_id', $item['slot_id'])
                    ->where('session_id', $purchaseData['session_id'])
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

        Mail::to($purchaseData['email'])->queue(new PurchaseMailer($purchase->load('items')));

        return $purchase->load('items');
    }

    private function calculateTotal(array $itemsData): float
    {
        return collect($itemsData)->sum(fn($item) => $item['quantity'] * $item['price']);
    }
}
