<?php

namespace App\Notifications;

use App\Http\Controllers\WhatsappController;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;

class WhatsappNotification
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        if (!method_exists($notification, 'toWhatsApp')) {
            throw new \Exception('The toWhatsApp method is not defined in the notification.');
        }
        
        $message = $notification->toWhatsapp($notifiable);

        $request = new Request([
            'message' => $message['body'],
            'mobile' => $message['mobile'],
            'language' => app()->getLocale() == 'ar' ? 2 : 1
        ]);
        
        // Send the whatsapp notification
        $whatsapp = new WhatsappController();
        $message['mobile'] ? $whatsapp->sendTextMessage($request) : '';
    }
}
