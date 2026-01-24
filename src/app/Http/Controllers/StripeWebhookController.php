<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use Stripe\Webhook;


class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $secret);
        } catch (\Exception $e) {
            return response('', 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;

                $purchaseId = $session->metadata->purchase_id ?? null;
                if ($purchaseId) {
                    Purchase::where('id', $purchaseId)->update(['status' => 'paid']);
                }
                break;

            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $purchaseId = $paymentIntent->metadata->purchase_id ?? null;
                if ($purchaseId) {
                    Purchase::where('id', $purchaseId)->update(['status' => 'failed']);
                }
                break;
        }
        return response('', 200);
    }
}
