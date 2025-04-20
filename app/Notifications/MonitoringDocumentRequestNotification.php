<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MonitoringDocumentRequestNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $advisorName;

    public function __construct($advisorName)
    {
        $this->advisorName = $advisorName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Permintaan Dokumen Monitoring')
            ->greeting('Halo Admin,')
            ->line("Guru {$this->advisorName} memerlukan dokumen monitoring.")
            ->line('Silakan generate dokumen monitoring sesegera mungkin.')
            ->action('Kelola Monitoring', route('admin.monitoring'))
            ->line('Terima kasih atas kerja samanya.');
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
