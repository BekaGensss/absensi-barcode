<?php

namespace App\Notifications;

use App\Models\Pengumuman;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AnnouncementNotification extends Notification
{
    use Queueable;

    protected $pengumuman;

    public function __construct(Pengumuman $pengumuman)
    {
        $this->pengumuman = $pengumuman;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Pengumuman Baru: ' . $this->pengumuman->judul)
                    ->greeting('Hallo!')
                    ->line('Ada pengumuman baru yang diterbitkan di absensi kehadiran.')
                    ->line('Judul: ' . $this->pengumuman->judul)
                    ->line('Isi: ' . $this->pengumuman->isi)
                    ->action('Lihat Pengumuman', url('/pengumuman'));
    }
}