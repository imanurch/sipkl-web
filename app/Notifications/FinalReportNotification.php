<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FinalReportNotification extends Notification
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
            ->subject('Laporan Akhir Sudah Diunggah')
            ->greeting('Halo Bapak/Ibu Guru Pembimbing,')
            ->line("Siswa bimbingan Anda, {$this->studentName}, telah mengunggah laporan akhir PKL.")
            ->line('Silakan tinjau laporan dan lakukan penilaian sesegera mungkin.')
            ->action('Lihat Laporan Akhir', route('advisor.assessment'))
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
