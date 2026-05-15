<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center bg-[#1a2344] p-4">
    <div class="w-full max-w-[500px] bg-white rounded-[40px] shadow-2xl p-10">
        <!-- Icon Area -->
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-[#4353ff] rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
            </div>
        </div>

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-[28px] font-bold text-[#1a2344] mb-2">Sistema de Gestion de Tareas</h1>
            <p class="text-slate-400 text-sm">Departamento de Infraestructura y TI</p>
        </div>

        <!-- Login Form -->
        <form wire:submit="login" class="space-y-5">
            <div>
                <label for="username" class="block text-sm font-semibold text-[#1a2344] mb-2">Usuario o Correo</label>
                <input wire:model="form.username" id="username" type="text" placeholder="admin o usuario@empresa.com" 
                    class="w-full px-5 py-4 bg-[#f8f9fc] border-0 rounded-2xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all text-[#1a2344] placeholder:text-slate-400">
                <x-input-error :messages="$errors->get('form.username')" class="mt-2" />
            </div>

            <div>
                <label for="password" class="block text-sm font-semibold text-[#1a2344] mb-2">Contrasena</label>
                <input wire:model="form.password" id="password" type="password" 
                    class="w-full px-5 py-4 bg-[#f8f9fc] border-0 rounded-2xl focus:ring-2 focus:ring-blue-500/20 outline-none transition-all text-[#1a2344]">
                <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
            </div>

            <button type="submit" class="w-full py-4 bg-[#3b49ff] hover:bg-[#3240e6] text-white font-bold rounded-2xl shadow-lg shadow-blue-500/20 transition-all">
                Iniciar Sesion
            </button>
        </form>

        <!-- Quick Access -->
        <div class="mt-10">
            <p class="text-center text-sm font-bold text-slate-500 mb-6">Accesos rapidos</p>
            
            <div class="space-y-4">
                <!-- Probar App -->
                <button type="button" class="w-full flex items-center gap-4 p-4 rounded-2xl bg-[#f8f9fc] border-2 border-dashed border-[#d1d5ff] group hover:border-blue-300 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-[#d946ef] flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-bold text-[#1a2344]">Probar App</p>
                        <p class="text-xs text-[#d946ef]">Modo demo sin base de datos</p>
                    </div>
                </button>

                <!-- Administrador -->
                <button type="button" onclick="document.getElementById('username').value='admin'; document.getElementById('username').dispatchEvent(new Event('input'))" class="w-full flex items-center gap-4 p-4 rounded-2xl bg-[#f8f9fc] hover:bg-slate-100 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-[#4353ff] flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-bold text-[#1a2344]">Administrador</p>
                        <p class="text-xs text-slate-400">Gestion completa del sistema</p>
                    </div>
                </button>

                <!-- Operario -->
                <button type="button" onclick="document.getElementById('username').value='employee'; document.getElementById('username').dispatchEvent(new Event('input'))" class="w-full flex items-center gap-4 p-4 rounded-2xl bg-[#f8f9fc] hover:bg-slate-100 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-[#10b981] flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15 3 3-3 3"/><path d="M15 13l-9.5 9.5a3 3 0 1 1-4.24-4.24l9.5-9.5"/><path d="M14 11l.5-.5a2.12 2.12 0 0 1 3 3l-.5.5"/><path d="M18 19h3"/><path d="M21 16v3"/></svg>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-bold text-[#1a2344]">Operario / Empleado</p>
                        <p class="text-xs text-slate-400">Ve sus tareas asignadas</p>
                    </div>
                </button>

                <!-- Solicitante -->
                <button type="button" class="w-full flex items-center gap-4 p-4 rounded-2xl bg-[#f8f9fc] hover:bg-slate-100 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-[#ff8c00] flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                    </div>
                    <div class="text-left">
                        <p class="text-sm font-bold text-[#1a2344]">Solicitante</p>
                        <p class="text-xs text-slate-400">Solicita tareas y ve su estado</p>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>
