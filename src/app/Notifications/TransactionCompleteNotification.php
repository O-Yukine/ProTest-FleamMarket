<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Chat;

class TransactionCompleteNotification extends Notification
{
    use Queueable;

    protected $chat;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->chat->product->name . 'の取引が完了しました')
            ->line($this->chat->buyer->name . 'さんとの取引が完了し、あなたの評価が更新されました。')
            ->action($this->chat->buyer->name . 'さんを評価する', url('/chat-room/' . $this->chat->id))
            ->line('happy trading!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
