<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LogbookNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $studentName;

    public function __construct($studentName)
    {
        $this->studentName = $studentName;
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
            ->subject('Logbook Perlu Konfirmasi')
            ->greeting('Halo Bapak/Ibu Guru Pembimbing,')
            ->line("Siswa bimbingan Anda, {$this->studentName}, telah mengisi logbook PKL.")
            ->line('Silakan tinjau isian logbook dan lakukan konfirmasi sesegera mungkin.')
            ->action('Lihat Logbook', route('advisor.logbook'))
            ->line('Terima kasih atas bimbingannya.');
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
