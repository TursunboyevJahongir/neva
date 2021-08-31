<?php

namespace App\Services\Payments;

use App\Models\Invoice;

interface PaymentService {
    public function getUrlFor(Invoice $invoice);
}