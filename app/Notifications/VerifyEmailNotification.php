<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
    protected function buildMailMessage($url)
    {
        // $this->notifiable TIDAK bisa dipakai di sini
        // Gunakan $url langsung saja untuk tombol verifikasi

        return (new MailMessage)
            ->subject('Verifikasi Email — MyGallery')
            ->view('emails.verify-email', [
                'url' => $url,
                // ↑ Hanya kirim url saja
                // Data user akan diambil langsung di blade via $notifiable
            ]);
    }

    public function toMail($notifiable)
    {
        // Override toMail agar bisa kirim $notifiable ke view
        $url = $this->verificationUrl($notifiable);
        // ↑ Generate URL verifikasi yang benar

        return (new MailMessage)
            ->subject('Verifikasi Email — MyGallery')
            ->view('emails.verify-email', [
                'url' => $url,
                'user' => $notifiable,
                // ↑ Di sini baru bisa akses $notifiable (object User)
            ]);
    }
}