<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupportNotification extends Notification
{
    use Queueable;

    public $user;
    public $channels;
    public $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($user,$message='',$channels='')
    {
        $this->user = $user;
        $this->channels = $channels;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {   
        $customChannels = [
            'sms' => SMSNotification::class,
            'whatsapp' => WhatsappNotification::class,
            'mail' => 'mail',
        ];

        $formattedChannels = $this->channels != '' ?  explode(',', $this->channels): [];
        $formattedChannels = array_map(function ($channel) use ($customChannels) { return $customChannels[$channel] ?? $channel; },$formattedChannels);
        return count($formattedChannels) ? $formattedChannels : $customChannels["whatsapp"];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable)//: MailMessage
    {
        // return (new MailMessage)
        //             ->line('The introduction to the notification.')
        //             ->action('Notification Action', url('/'))
        //             ->line('Thank you for using our application!');
    }

        /**
     * Get the mail representation of the notification.
     */
    public function toSms(object $notifiable)//: MailMessage
    {
        if (!$this->user || !$this->user->phone) return false;

        $message = $this->message ?? __("We've just sent you a message on :app_name app support.", ["app_name" => config("app.name")]);

        return [
            'body' => $message,
            'mobile' => strrpos($this->user->phone, '0',0) === 0 ? $this->user->phone : '0' . $this->user->phone
        ];
    }

    /**
     * Get the Whatapp representation of the notification.
     */
    public function toWhatsapp(object $notifiable)
    {
        if (!$this->user || !$this->user->phone) return false;

        $message = $this->message ?? __("We've just sent you a message on :app_name app support.", ["app_name" => config("app.name")]);

        return [
            'body' => $message,
            'mobile' => strrpos($this->user->phone, '0',0) === 0 ? $this->user->phone : '0' . $this->user->phone
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
