<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav class="bg-[#1a2344] border-b border-white/5 px-6 py-3 flex items-center justify-between sticky top-0 z-50">
    <!-- Left: Logo & Info -->
    <div class="flex items-center gap-4">
        <div class="w-10 h-10 bg-[#4353ff] rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
        </div>
        <div>
            <h2 class="text-white font-bold leading-tight">Gestion de Tareas</h2>
            <div class="flex items-center gap-2">
                <span class="text-[10px] text-blue-200/60 font-medium uppercase">Infraestructura y TI</span>
                @if(auth()->user()->role === 'admin')
                    <span class="bg-blue-500/20 text-blue-400 text-[9px] px-1.5 py-0.5 rounded-full font-bold uppercase border border-blue-400/20">Administrador</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Center: Main Nav -->
    <div class="flex items-center gap-1">
        <x-nav-button :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="dashboard">Dashboard</x-nav-button>
        <x-nav-button :href="route('nueva-solicitud')" :active="request()->routeIs('nueva-solicitud')" icon="plus">Nueva</x-nav-button>
        <x-nav-button :href="route('mis-tareas')" :active="request()->routeIs('mis-tareas')" icon="tasks">Mis Tareas</x-nav-button>
        <x-nav-button :href="route('equipo')" :active="request()->routeIs('equipo')" icon="users">Equipo</x-nav-button>
        <x-nav-button :href="route('usuarios')" :active="request()->routeIs('usuarios')" icon="user-plus">Usuarios</x-nav-button>
        <x-nav-button :href="route('proyectos')" :active="request()->routeIs('proyectos')" icon="folder">Proyectos</x-nav-button>
        <x-nav-button :href="route('informes')" :active="request()->routeIs('informes')" icon="chart">Informes</x-nav-button>
        <x-nav-button :href="route('archivo')" :active="request()->routeIs('archivo')" icon="archive">Archivo</x-nav-button>
    </div>

    <!-- Right: Actions & Profile -->
    <div class="flex items-center gap-4">
        <button class="text-white/60 hover:text-white transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
        </button>
        <div class="flex items-center gap-3 pl-4 border-l border-white/10">
            <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-bold ring-2 ring-white/10 shadow-lg">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            
            <!-- Logout Button -->
            <button wire:click="logout" class="text-white/40 hover:text-red-400 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
            </button>
        </div>
    </div>
</nav>
