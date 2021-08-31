<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Models\Invoice;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class InvoiceService {

    public function createFor(Order $order)
    {
        $invoice = Invoice::create([
            'order_id' => $order->id,
            'amount' => $order->sum * 100
        ]);
        return $invoice;
    }
}