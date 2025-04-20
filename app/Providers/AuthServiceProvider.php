<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verifikasi Alamat Email')
                ->greeting('Halo, ' . ($notifiable->username ?? 'User'))
                ->line('Silakan klik tombol di bawah ini untuk memverifikasi email Anda yang terdaftar pada Sistem Informasi Praktik Kerja Lapangan (SIPKL) SMK N 1 Pajangan.')
                ->action('Verifikasi Sekarang', $url)
                ->line('Jika Anda tidak membuat akun ini, abaikan email ini.');
        });
    }
}
