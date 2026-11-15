<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function overview()
    {
        return response()->json([
            'signups' => User::count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'confirmed_income_paisa' => (int) Payment::where('status', 'confirmed')->sum('amount_paisa'),
            'confirmed_income_label' => formatPkr((int) Payment::where('status', 'confirmed')->sum('amount_paisa')),
            'orders_by_status' => Order::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
        ]);
    }

    public function users()
    {
        $users = User::with('profile:id,user_id,organization')
            ->orderByDesc('id')
            ->limit(200)
            ->get()
            ->map(fn (User $u) => [
                'id' => $u->id,
                'full_name' => $u->full_name,
                'email' => $u->email,
                'username' => $u->username,
                'type' => $u->type,
                'card_url' => url('/' . $u->username),
                'email_verified' => (bool) $u->email_verified_at,
            ]);

        return response()->json(['users' => $users]);
    }

    public function payments()
    {
        $rows = Payment::with('user:id,first_name,last_name,email')
            ->orderByDesc('id')
            ->limit(100)
            ->get()
            ->map(fn (Payment $p) => [
                'id' => $p->id,
                'user' => $p->user ? ($p->user->full_name . ' <' . $p->user->email . '>') : null,
                'amount_label' => formatPkr($p->amount_paisa),
                'method' => $p->method,
                'purpose' => $p->purpose,
                'status' => $p->status,
                'reference' => $p->reference,
                'created_at' => $p->created_at->toIso8601String(),
            ]);

        return response()->json(['payments' => $rows]);
    }

    public function confirmPayment(Request $request, int $id)
    {
        $payment = Payment::findOrFail($id);
        if ($payment->status === 'confirmed') {
            return response()->json(['message' => 'Already confirmed.']);
        }

        $payment->status = 'confirmed';
        $payment->confirmed_by = $request->user()->id;
        $payment->confirmed_at = now();
        $payment->save();

        if ($payment->subscription_id) {
            $sub = Subscription::find($payment->subscription_id);
            $plan = $sub?->plan_id ? Plan::find($sub->plan_id) : null;
            if ($sub) {
                $months = $plan?->months ?? 12;
                $sub->status = 'ACTIVE';
                $sub->current_period_start = now();
                $sub->current_period_end = now()->addMonths($months);
                $sub->trial_ends_at = null;
                $sub->save();
            }
        }

        return response()->json(['message' => 'Payment confirmed. Subscription is active.']);
    }

    public function orders()
    {
        $orders = Order::with('user:id,first_name,last_name,username')
            ->orderByDesc('id')
            ->limit(100)
            ->get();

        return response()->json(['orders' => $orders]);
    }

    public function updateOrderStatus(Request $request, int $id)
    {
        $data = $request->validate([
            'status' => 'required|string|max:50',
        ]);
        $order = Order::findOrFail($id);
        $order->status = $data['status'];
        $order->save();
        return response()->json(['message' => 'Order updated.', 'order' => $order]);
    }

    public function plans()
    {
        return response()->json(['plans' => Plan::orderBy('id')->get()]);
    }

    public function updatePlan(Request $request, int $id)
    {
        $data = $request->validate([
            'price_paisa' => 'required|integer|min:0|max:' . maxPricePaisa(),
            'is_active' => 'sometimes|boolean',
            'badge' => 'nullable|string|max:50',
        ]);
        $plan = Plan::findOrFail($id);
        $plan->fill($data);
        $plan->save();
        return response()->json(['message' => 'Plan saved.', 'plan' => $plan]);
    }

    public function exportUsersCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="users.csv"',
        ];

        return response()->stream(function () {
            echo "\xEF\xBB\xBF"; // UTF-8 BOM for Urdu names
            $out = fopen('php://output', 'w');
            fputcsv($out, ['id', 'name', 'email', 'username', 'type']);
            User::orderBy('id')->chunk(200, function ($chunk) use ($out) {
                foreach ($chunk as $u) {
                    fputcsv($out, [
                        $u->id,
                        $this->csvCell($u->full_name),
                        $this->csvCell($u->email),
                        $this->csvCell($u->username),
                        $this->csvCell($u->type),
                    ]);
                }
            });
            fclose($out);
        }, 200, $headers);
    }

    protected function csvCell(?string $value): string
    {
        $value = (string) $value;
        if ($value !== '' && in_array($value[0], ['=', '+', '-', '@'], true)) {
            $value = "'" . $value;
        }
        return $value;
    }
}
