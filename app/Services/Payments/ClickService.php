<?php

namespace App\Services\Payments;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Transaction;
use App\Exceptions\PaymentException;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class ClickService implements PaymentService {

    const SYSTEM_NAME     = 'Click';

    const STATE_CREATED   = 1;
    const STATE_COMPLETED = 2;
    const STATE_CANCELLED = -1;

    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getUrlFor(Invoice $invoice)
    {
        $url = 'https://my.click.uz/services/pay?'
             . 'service_id=' . config('payments.click_service_id') . '&'
             . 'merchant_id=' . config('payments.click_merchant_id') . '&'
             . 'amount=' . intval($invoice->amount / 100) . '.00' . '&'
             . 'transaction_param=' . $invoice->id . '&'
             . 'return_url=' . route('thanks');
        return $url;
    }

    // basic auth later
    public function authorize($auth)
    {
    }

    public function Check()
    {
        if (!$this->request->has(['click_trans_id', 'service_id', 'merchant_trans_id', 'amount',
            'action', 'error', 'error_note', 'sign_time', 'sign_string', 'click_paydoc_id'])
            || $this->request->input('action') == 1 && !$this->request->has('merchant_prepare_id')) {
            throw new PaymentException($this->request, PaymentException::CLICK_INVALID_REQUEST);
        }

        $sign = md5(
            $this->request->input('click_trans_id') .
            $this->request->input('service_id') .
            config('payments.click_secret_key') .
            $this->request->input('merchant_trans_id') .
            ($this->request->input('action') == 1 ? $this->request->input('merchant_prepare_id') : '') .
            $this->request->input('amount') .
            $this->request->input('action') .
            $this->request->input('sign_time')
        );

        if ($sign != $this->request->input('sign_string')) {
            throw new PaymentException($this->request, PaymentException::CLICK_SIGN_CHECK_FAILED);
        }

        if ($this->request->input('action') != 0 && $this->request->input('action') != 1) {
            throw new PaymentException($this->request, PaymentException::CLICK_ACTION_NOT_FOUND);
        }

        $invoiceId = $this->request->input('merchant_trans_id');
        try {
            $invoice = Invoice::findOrFail($invoiceId);
        } catch (\Exception $e) {
            throw new PaymentException($this->request, PaymentException::CLICK_INVALID_ACCOUNT);
        }

        if ($invoice->status == Invoice::STATUS_PAID) {
            throw new PaymentException($this->request, PaymentException::CLICK_ALREADY_PAID);
        }
        if ($invoice->status == Invoice::STATUS_CANCELLED) {
            throw new PaymentException($this->request, PaymentException::CLICK_TRANSACTION_CANCELLED);
        }

        $amount = intval(floatval($this->request->input('amount')) * 100);
        if ($invoice->amount != $amount) {
            throw new PaymentException($this->request, PaymentException::CLICK_INVALID_AMOUNT);
        }
    }

    public function Prepare()
    {
        $transactionId = $this->request->input('click_trans_id');
        $transaction = Transaction::where('system_transaction_id', $transactionId)->first();
        if ($transaction) {
            throw new PaymentException($this->request, PaymentException::CLICK_INVALID_ACCOUNT);
        }

        $transaction = Transaction::create([
            'system' => self::SYSTEM_NAME,
            'system_transaction_id' => $this->request->input('click_trans_id'),
            'status' => self::STATE_CREATED,
            'amount' => intval(floatval($this->request->input('amount')) * 100),
            'invoice_id' => $this->request->input('merchant_trans_id')
        ]);

        return [
            'click_trans_id'      => $this->request->input('click_trans_id'),
            'merchant_trans_id'   => $this->request->input('merchant_trans_id'),
            'merchant_prepare_id' => $transaction->id,
            'error'               => 0,
            'error_note'          => 'Success'
        ];
    }

    public function Complete()
    {
        $transactionId = $this->request->input('merchant_prepare_id');
        $transaction = Transaction::find($transactionId);
        if (!$transaction) {
            throw new PaymentException($this->request, PaymentException::CLICK_TRANSACTION_NOT_FOUND);
        }

        $invoice = Invoice::find($this->request->input('merchant_trans_id'));
        if ($this->request->input('error') == 0) {
            $transaction->performed_at = Carbon::now();
            $transaction->status = self::STATE_COMPLETED;
            $transaction->save();

            $invoice->status = Invoice::STATUS_PAID;
            $invoice->order->status = Order::STATUS_PENDING;
            $invoice->push();
        }
        if ($this->request->input('error') < 0) {
            $transaction->cancelled_at = Carbon::now();
            $transaction->status = self::STATE_CANCELLED;
            $transaction->save();

            $invoice->status = Invoice::STATUS_CANCELLED;
            $invoice->order->status = Order::STATUS_CANCELLED;
            $invoice->push();

            return [
                'click_trans_id'      => $this->request->input('click_trans_id'),
                'merchant_trans_id'   => $this->request->input('merchant_trans_id'),
                'merchant_confirm_id' => $transaction->id,
                'error'               => -4,
                'error_note'          => 'Cancelled'
            ];
        }

        return [
            'click_trans_id'      => $this->request->input('click_trans_id'),
            'merchant_trans_id'   => $this->request->input('merchant_trans_id'),
            'merchant_confirm_id' => $transaction->id,
            'error'               => 0,
            'error_note'          => 'Success'
        ];
    }
}