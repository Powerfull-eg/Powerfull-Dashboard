<?php

namespace App\Notifications;

use App\Http\Controllers\SMSController;
use App\Http\Controllers\WhatsappController;
use App\Models\User;
use App\Notifications\WhatsappNotification;
use App\Notifications\SMSNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $channels;
    protected $token;
    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($token,User $user,$channels='')
    {
        $this->token = $token;
        $this->channels = $channels;
        $this->user = $user;
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
        return count($formattedChannels) ? $formattedChannels : ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $url = url(route('dashboard.password.reset', $this->token, false));

        return (new MailMessage)
                    ->subject('Reset Password Notification')
                    ->line('You are receiving this email because we received a password reset request for your account.')
                    ->action('Reset Password', $url)
                    ->line('If you did not request a password reset, no further action is required.');
    }
    /**
     * Get the sms representation of the notification.
     */
    public function toSms($notifiable)
    {
        if (!$this->user || !$this->user->phone) return false;

        $url = url(route('website.password.reset', ['token' => $this->token], false));
        $message = "Reset your password using this link:\n\n $url";

        return [
            'body' => $message,
            'mobile' => $this->user->phone
        ];
    }

    /**
     * Get the whatsapp representation of the notification.
     */
    public function toWhatsapp($notifiable)
    {
        if (!$this->user || !$this->user->phone) return false;

        $url = url(route('website.password.reset', ['token' => $this->token], false));
        $message = "Reset your password using this link:\n\n $url";

        return [
            'body' => $message,
            'mobile' => $this->user->phone
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
