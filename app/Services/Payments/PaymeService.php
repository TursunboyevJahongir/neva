<?php

namespace App\Services\Payments;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Transaction;
use App\Exceptions\PaymentException;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class PaymeService implements PaymentService {

    const SYSTEM_NAME                    = 'Payme';

    const STATE_CREATED                  = 1;
    const STATE_COMPLETED                = 2;
    const STATE_CANCELLED                = -1;
    const STATE_CANCELLED_AFTER_COMPLETE = -2;

    const REASON_RECEIVERS_NOT_FOUND         = 1;
    const REASON_PROCESSING_EXECUTION_FAILED = 2;
    const REASON_EXECUTION_FAILED            = 3;
    const REASON_CANCELLED_BY_TIMEOUT        = 4;
    const REASON_FUND_RETURNED               = 5;
    const REASON_UNKNOWN                     = 10;

    const TRANSACTION_TIMEOUT = 12; // hours

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    // https://developer.help.paycom.uz/ru/initsializatsiya-platezhey/otpravka-cheka-po-metodu-get
    public function getUrlFor(Invoice $invoice)
    {
        $url = 'https://checkout.paycom.uz/';
        if (config('app.env') == 'local') {
            $url = 'https://test.paycom.uz/';
        }

        $payload = 'm=' . config('payments.payme_merchant_id') . ';'
                 . 'ac.invoice_id=' . $invoice->id . ';'
                 . 'a=' . $invoice->amount . ';'
                 . 'l=' . config('app.locale');

        return $url . base64_encode($payload);
    }

    /// MERCHANT API
    /// https://developer.help.paycom.uz/ru/metody-merchant-api

    public function authorize()
    {
        $auth = $this->request->header('Authorization');
        $credentials = config('payments.payme_login') . ":" . config('payments.payme_cashier_key');
        if (!$auth || !preg_match('/^\s*Basic\s+(\S+)\s*$/i', $auth, $matches) ||
            base64_decode($matches[1]) != $credentials) {
            throw new PaymentException($this->request, PaymentException::PAYME_INSUFFICIENT_PRIVILEGE);
        }
    }

    public function CheckPerformTransaction()
    {
        $invoiceId = $this->request->input('params.account.invoice_id');
        if (!$invoiceId) {
            throw new PaymentException($this->request, PaymentException::PAYME_INVALID_ACCOUNT);
        }

        try {
            $invoice = Invoice::findOrFail($invoiceId);
        } catch (\Exception $e) {
            throw new PaymentException($this->request, PaymentException::PAYME_INVALID_ACCOUNT);
        }

        // if paid or cancelled - cannot perform transaction
        if ($invoice->status != Invoice::STATUS_NEW) {
            throw new PaymentException($this->request, PaymentException::PAYME_INVALID_ACCOUNT);
        }

        // transaction found either pending, either finished, either cancelled - cannot pay once more
        $transaction = Transaction::where('invoice_id', $invoice->id)->first();
        if ($transaction) {
            throw new PaymentException($this->request, PaymentException::PAYME_INVALID_ACCOUNT);
        }

        if ($invoice->amount != $this->request->input('params.amount')) {
            throw new PaymentException($this->request, PaymentException::PAYME_INVALID_AMOUNT);
        }

        return [
            'allow' => true
        ];
    }

    public function CheckTransaction()
    {
        $transactionId = $this->request->input('params.id');
        $transaction = Transaction::where('system_transaction_id', $transactionId)->first();

        if (!$transaction) {
            throw new PaymentException($this->request, PaymentException::PAYME_TRANSACTION_NOT_FOUND);
        }

        return [
            'create_time'  => $transaction->created_at->timestamp * 1000,
            'perform_time' => $transaction->performed_at ? ($transaction->performed_at->timestamp * 1000) : 0,
            'cancel_time'  => $transaction->cancelled_at ? ($transaction->cancelled_at->timestamp * 1000) : 0,
            'transaction'  => strval($transaction->id),
            'state'        => $transaction->status,
            'reason'       => $transaction->reason ? intval($transaction->reason) : null
        ];
    }

    public function CreateTransaction()
    {
        $transactionId = $this->request->input('params.id');
        $transaction = Transaction::where('system_transaction_id', $transactionId)->first();

        if ($transaction) {
            if ($transaction->status != self::STATE_CREATED) {
                // validate transaction state
                throw new PaymentException($this->request, PaymentException::PAYME_COULD_NOT_PERFORM);
            } elseif ($transaction->status == self::STATE_CREATED
                    && Carbon::now()->diffInHours($transaction->created_at) >= 12) {
                // if transaction timed out, cancel it and send error
                $transaction->cancelled_at = Carbon::now();

                if ($transaction->status == self::STATE_COMPLETED) {
                    $transaction->status = self::STATE_CANCELLED_AFTER_COMPLETE;
                } else {
                    $transaction->status = self::STATE_CANCELLED;
                }

                $transaction->reason = self::REASON_CANCELLED_BY_TIMEOUT;
                $transaction->save();

                throw new PaymentException($this->request, PaymentException::PAYME_COULD_NOT_PERFORM);
            } else {
                return [
                    'create_time' => $transaction->created_at->timestamp * 1000,
                    'transaction' => strval($transaction->id),
                    'state'       => intval($transaction->status),
                    'receivers'   => null
                ];
            }
        } else { // transaction not found, create new one

            $this->CheckPerformTransaction();

            $time = Carbon::createFromTimestampMs($this->request->input('params.time'));
            // validate new transaction time
            if (Carbon::now()->diffInHours($time) >= 12) {
                throw new PaymentException($this->request, PaymentException::PAYME_INVALID_ACCOUNT);
            }

            $transaction = Transaction::create([
                'system' => self::SYSTEM_NAME,
                'system_transaction_id' => $this->request->input('params.id'),
                'status' => self::STATE_CREATED,
                'amount' => $this->request->input('params.amount'),
                'invoice_id' => $this->request->input('params.account.invoice_id'),
                'created_at' => $time
            ]);

            return [
                'create_time' => $transaction->created_at->timestamp * 1000,
                'transaction' => strval($transaction->id),
                'state'       => intval($transaction->status),
                'receivers'   => null,
            ];
        }
    }

    public function PerformTransaction()
    {
        $transactionId = $this->request->input('params.id');
        $transaction = Transaction::where('system_transaction_id', $transactionId)->first();

        if (!$transaction) {
            throw new PaymentException($this->request, PaymentException::PAYME_TRANSACTION_NOT_FOUND);
        }

        switch (intval($transaction->status)) {
            case self::STATE_CREATED: // handle active transaction
                if (Carbon::now()->diffInHours($transaction->created_at) >= 12) {
                    // if transaction timed out, cancel it and send error
                    $transaction->cancelled_at = Carbon::now();

                    if ($transaction->status == self::STATE_COMPLETED) {
                        $transaction->status = self::STATE_CANCELLED_AFTER_COMPLETE;
                    } else {
                        $transaction->status = self::STATE_CANCELLED;
                    }

                    $transaction->reason = self::REASON_CANCELLED_BY_TIMEOUT;
                    $transaction->save();

                    throw new PaymentException($this->request, PaymentException::PAYME_COULD_NOT_PERFORM);
                } else {
                    $invoice = $transaction->invoice;
                    $invoice->status = Invoice::STATUS_PAID;
                    $invoice->order->status = Order::STATUS_PENDING;
                    $invoice->push();

                    $transaction->performed_at = Carbon::now();
                    $transaction->status = self::STATE_COMPLETED;
                    $transaction->save();

                    return [
                        'transaction'  => strval($transaction->id),
                        'perform_time' => $transaction->performed_at->timestamp * 1000,
                        'state'        => intval($transaction->status),
                    ];
                }
                break;

            case self::STATE_COMPLETED:
                return [
                    'transaction'  => strval($transaction->id),
                    'perform_time' => $transaction->performed_at->timestamp * 1000,
                    'state'        => intval($transaction->status),
                ];
                break;
            default:
                throw new PaymentException($this->request, PaymentException::PAYME_COULD_NOT_PERFORM);
                break;
        }
    }

    public function CancelTransaction()
    {
        $transactionId = $this->request->input('params.id');
        $transaction = Transaction::where('system_transaction_id', $transactionId)->first();

        if (!$transaction) {
            throw new PaymentException($this->request, PaymentException::PAYME_TRANSACTION_NOT_FOUND);
        }

        switch (intval($transaction->status)) {
            case self::STATE_CANCELLED:
            case self::STATE_CANCELLED_AFTER_COMPLETE:
                return [
                    'transaction' => strval($transaction->id),
                    'cancel_time' => $transaction->cancelled_at->timestamp * 1000,
                    'state'       => intval($transaction->status)
                ];
                break;
            case self::STATE_CREATED:
                $transaction->reason = $this->request->input('params.reason');
                $transaction->cancelled_at = Carbon::now();
                if ($transaction->status == self::STATE_COMPLETED) {
                    $transaction->status = self::STATE_CANCELLED_AFTER_COMPLETE;
                } else {
                    $transaction->status = self::STATE_CANCELLED;
                }
                $transaction->invoice->status = Invoice::STATUS_CANCELLED;
                $transaction->invoice->order->status = Order::STATUS_CANCELLED;
                $transaction->push();

                return [
                    'transaction' => strval($transaction->id),
                    'cancel_time' => $transaction->cancelled_at->timestamp * 1000,
                    'state'       => intval($transaction->status),
                ];
                break;

            case self::STATE_COMPLETED:
                /*$order = $transaction->order;
                if ($order->status != Order::STATUS_SHIPPED &&
                    $order->status != Order::STATUS_FINISHED &&
                    $order->status != Order::STATUS_CANCELLED) {

                    $order->status = Order::STATUS_CANCELLED;
                    $order->save();

                    $transaction->reason = $this->request->input('params.reason');
                    $transaction->cancelled_at = Carbon::now();
                    if ($transaction->status == self::STATE_COMPLETED) {
                        $transaction->status = self::STATE_CANCELLED_AFTER_COMPLETE;
                    } else {
                        $transaction->status = self::STATE_CANCELLED;
                    }
                    $transaction->save();

                    return [
                        'transaction' => strval($transaction->id),
                        'cancel_time' => $transaction->cancelled_at->timestamp * 1000,
                        'state'       => intval($transaction->status),
                    ];
                }*/ // TODO: cancel paid invoice? not possible
                throw new PaymentException($this->request, PaymentException::PAYME_COULD_NOT_CANCEL);
                break;
        }
    }

    public function GetStatement()
    {
        try {
            $from = Carbon::createFromTimestampMs($this->request->input('params.from'));
            $to = Carbon::createFromTimestampMs($this->request->input('params.to'));
        } catch (\Exception $e) {
            throw new PaymentException($this->request, PaymentException::PAYME_INVALID_ACCOUNT);
        }

        // validate
        if (!$from || !$to || $from->gt($to)) {
            throw new PaymentException($this->request, PaymentException::PAYME_INVALID_ACCOUNT);
        }

        $transactions = Transactions::with('order')->where('system', self::SYSTEM_NAME)->
            whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->get();
        $result = [];
        foreach ($transactions as $transaction)
        {
            $result[] = [
                'id'      => $transation->system_transaction_id,
                'time'    => $transaction->created_at->timestamp * 1000,
                'amount'  => $transaction->amount,
                'account' => [
                    'invoice_id' => $transaction->invoice_id
                ],
                'create_time'  => $transaction->created_at->timestamp * 1000,
                'perform_time' => $transaction->performed_at ? ($transaction->performed_at->timestamp * 1000) : 0,
                'cancel_time'  => $transaction->cancelled_at ? ($transaction->cancelled_at->timestamp * 1000) : 0,
                'transaction'  => strval($transaction->id),
                'state'        => intval($transaction->status),
                'reason'       => $transaction->reason ? intval($transaction->reason) : null
            ];
        }

        return [
            'transactions' => $result
        ];
    }
}
