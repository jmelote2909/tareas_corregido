<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $mailMailer = \App\Models\Setting::get('mail_mailer');
                $mailUsername = \App\Models\Setting::get('mail_username');
                $mailPassword = \App\Models\Setting::get('mail_password');
                
                if ($mailUsername && $mailPassword) {
                    config([
                        'mail.mailers.smtp.username' => $mailUsername,
                        'mail.mailers.smtp.password' => $mailPassword,
                        'mail.from.address' => $mailUsername,
                    ]);
                    
                    if ($mailMailer) {
                        config([
                            'mail.default' => $mailMailer,
                        ]);
                    } else {
                        config([
                            'mail.default' => 'smtp',
                        ]);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Avoid failing during migrations or connection issues
        }
    }
}
