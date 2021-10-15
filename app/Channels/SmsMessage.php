<?php


namespace App\Channels;


use App\Services\External\Sms\EskizSmsService;

class SmsMessage
{
    protected string $to;
    protected string $message;
    private EskizSmsService $smsService;

    public function __construct()
    {
        $this->smsService = new EskizSmsService();
    }

    public function to($to): self
    {
        $this->to = $to;

        return $this;
    }

    public function message($message): self
    {
        $this->message = $message;

        return $this;
    }

    public function send()
    {
        return $this->smsService->send($this->to,$this->message);
    }
}
