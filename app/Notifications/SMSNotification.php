<?php

namespace App\Notifications;

use App\Http\Controllers\SMSController;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;

class SMSNotification
{

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if (!method_exists($notification, 'toSMS')) {
            throw new \Exception('The toSMS method is not defined in the notification.');
        }
        
        $message = $notification->toSMS($notifiable);
        
        $request = new Request([
            'message' => $message['body'],
            'mobile' => $message['mobile'],
            'language' => app()->getLocale() == 'ar' ? 2 : 1
        ]);
        
        // Send the whatsapp notification
        $sms = new SMSController();
        $message['mobile'] ? $sms->store($request) : '';
    }
}
