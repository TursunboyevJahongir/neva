<?php


namespace App\Channels;


use Illuminate\Notifications\Notification;

class SmsChannel
{
    /**
     * @param mixed $notifiable
     * @param Notification $notification
     */
    public function send(mixed $notifiable, Notification $notification)
    {
        $message = $notification->toSms($notifiable);
        $message->send();
    }
}
