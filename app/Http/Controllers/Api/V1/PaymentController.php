<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'method' => 'required|in:easypaisa,jazzcash,bank,cash,manual',
            'reference' => 'nullable|string|max:120',
            'notes' => 'nullable|string|max:1000',
        ]);

        $plan = Plan::findOrFail($data['plan_id']);
        $user = $request->user();

        $sub = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'status' => 'PENDING',
        ]);

        $payment = Payment::create([
            'user_id' => $user->id,
            'subscription_id' => $sub->id,
            'amount_paisa' => $plan->price_paisa,
            'method' => $data['method'],
            'purpose' => 'subscription',
            'status' => 'pending',
            'reference' => $data['reference'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        return response()->json([
            'message' => 'Payment submitted. We will confirm it by hand.',
            'payment' => [
                'id' => $payment->id,
                'amount_label' => formatPkr($payment->amount_paisa),
                'status' => $payment->status,
            ],
        ], 201);
    }

    public function mine(Request $request)
    {
        $rows = Payment::where('user_id', $request->user()->id)
            ->orderByDesc('id')
            ->limit(50)
            ->get()
            ->map(fn (Payment $p) => [
                'id' => $p->id,
                'amount_paisa' => $p->amount_paisa,
                'amount_label' => formatPkr($p->amount_paisa),
                'method' => $p->method,
                'purpose' => $p->purpose,
                'status' => $p->status,
                'created_at' => $p->created_at->toIso8601String(),
            ]);

        return response()->json(['payments' => $rows]);
    }
}
