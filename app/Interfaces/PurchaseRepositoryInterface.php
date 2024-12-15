<?php

namespace App\Interfaces;

use App\Models\Purchase;

interface PurchaseRepositoryInterface
{
    public function createPurchase(array $purchaseData, array $itemsData): Purchase;
}
