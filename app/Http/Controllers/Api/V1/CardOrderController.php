<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardOrderController extends Controller
{
    public function quote()
    {
        $cardPaisa = (int) (fromSettings('shop.card.pricePaisa', toPaisa(2500)));
        $deliveryPaisa = (int) (fromSettings('orders.shipping.flatPaisa', toPaisa(550)));
        $total = $cardPaisa + $deliveryPaisa;

        return response()->json([
            'lines' => [
                ['label' => 'Printed NFC card', 'amount_paisa' => $cardPaisa, 'amount_label' => formatPkr($cardPaisa)],
                ['label' => 'Delivery', 'amount_paisa' => $deliveryPaisa, 'amount_label' => formatPkr($deliveryPaisa)],
            ],
            'total_paisa' => $total,
            'total_label' => formatPkr($total),
            'button_label' => 'Order now — ' . formatPkr($total),
        ]);
    }

    public function show(Request $request)
    {
        $order = Order::where('user_id', $request->user()->id)->orderByDesc('id')->first();
        $statuses = $order
            ? OrderStatus::where('order_id', $order->id)->orderBy('id')->get()
            : collect();

        return response()->json([
            'order' => $order,
            'status_history' => $statuses,
            'quote' => $this->quote()->getData(true),
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'type' => 'nullable|string|max:50',
            'delivery_address' => 'required|string|max:500',
            'phone' => 'required|string|max:30',
        ]);

        $quote = $this->quote()->getData(true);
        $card = fromPaisa($quote['lines'][0]['amount_paisa']);
        $ship = fromPaisa($quote['lines'][1]['amount_paisa']);
        $total = fromPaisa($quote['total_paisa']);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $data['phone'],
                'delivery_address' => $data['delivery_address'],
                'status' => 'Pending',
                'price' => $card,
                'sale_price' => $card,
                'shipping' => $ship,
                'total' => $total,
                'discount' => 0,
                'coupon' => null,
                'type' => $data['type'] ?? 'standard',
            ]);

            OrderStatus::create([
                'order_id' => $order->id,
                'status' => 'Pending',
                'comment' => 'Order placed via API',
            ]);

            $user->username_locked = true;
            $user->save();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return response()->json([
            'message' => 'Order placed for ' . $quote['total_label'] . ' (card + delivery).',
            'order' => $order,
        ], 201);
    }
}
