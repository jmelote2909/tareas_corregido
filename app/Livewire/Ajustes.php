<?php

namespace App\Livewire;

use App\Models\Setting;
use Livewire\Component;

class Ajustes extends Component
{
    public $mailMailer = 'smtp';
    public $mailUsername = '';
    public $mailPassword = '';
    public $successMessage = '';

    public function mount()
    {
        // Guard access: only admin can access settings
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }

        $this->mailMailer = Setting::get('mail_mailer', 'smtp');
        $this->mailUsername = Setting::get('mail_username', '');
        $this->mailPassword = Setting::get('mail_password', '');
    }

    public function save()
    {
        $this->validate([
            'mailMailer' => 'required|in:smtp,resend',
            'mailUsername' => 'required_if:mailMailer,smtp|nullable|email',
            'mailPassword' => 'required_if:mailMailer,smtp|nullable|min:6',
        ], [
            'mailUsername.required_if' => 'El correo es obligatorio para configurar SMTP.',
            'mailUsername.email' => 'Por favor introduce una dirección de correo válida.',
            'mailPassword.required_if' => 'La contraseña de aplicación es obligatoria para SMTP.',
            'mailPassword.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        Setting::set('mail_mailer', $this->mailMailer);
        Setting::set('mail_username', $this->mailUsername);
        Setting::set('mail_password', $this->mailPassword);

        // Also update .env file values in config dynamically if needed,
        // but AppServiceProvider does this automatically next request.
        // Let's force load them in current request just in case.
        config([
            'mail.default' => $this->mailMailer,
            'mail.mailers.smtp.username' => $this->mailUsername,
            'mail.mailers.smtp.password' => $this->mailPassword,
            'mail.from.address' => $this->mailUsername,
        ]);

        $this->successMessage = 'Ajustes guardados con éxito.';
    }

    public function render()
    {
        return view('livewire.ajustes')->layout('layouts.app');
    }
}
