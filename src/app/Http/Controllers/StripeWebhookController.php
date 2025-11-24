<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Enums\PurchaseStatus;


class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        $eventType = $payload['type'] ?? null;

        switch ($eventType) {
            case 'checkout.session.completed':
                $session = $payload['data']['object'];
                $purchaseId = $session['metadata']['purchase_id'] ?? null;

                if ($purchaseId) {
                    $purchase = Purchase::find($purchaseId);
                    $purchase->status = 'paid';
                    $purchase->save();
                }
                break;

            case 'payment_intent.payment_failed':
                $intent = $payload['data']['object'];
                $purchaseId = $intent['metadata']['purchase_id'] ?? null;

                if ($purchaseId) {
                    $purchase = Purchase::find($purchaseId);
                    $purchase->status = 'failed';
                    $purchase->save();
                }
                break;
        }

        return response()->json(['received' => true]);
    }
}
