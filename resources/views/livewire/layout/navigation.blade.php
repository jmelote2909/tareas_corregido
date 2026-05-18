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

<nav x-data="{ open: false }" class="bg-[#1a2344] border-b border-white/5 px-4 md:px-6 py-3 flex flex-col sticky top-0 z-50">
    <div class="flex items-center justify-between w-full">
        <!-- Left: Logo & Info -->
        <div class="flex items-center gap-3 md:gap-4">
            <div class="w-10 h-10 bg-[#4353ff] rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/20 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
            </div>
            <div class="min-w-0">
                <h2 class="text-white font-bold leading-tight truncate text-sm md:text-base">Gestion de Tareas</h2>
                <div class="flex items-center gap-2">
                    <span class="text-[9px] md:text-[10px] text-blue-200/60 font-medium uppercase truncate">Infraestructura y TI</span>
                    @if(auth()->user()->role === 'admin')
                        <span class="bg-blue-500/20 text-blue-400 text-[8px] md:text-[9px] px-1.5 py-0.5 rounded-full font-bold uppercase border border-blue-400/20 shrink-0">Admin</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Center: Main Nav (Desktop) -->
        <div class="hidden xl:flex items-center gap-1">
            <x-nav-button :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="dashboard">Dashboard</x-nav-button>
            @if(in_array(auth()->user()->role, ['admin', 'requester', 'employee']))
                <x-nav-button :href="route('nueva-solicitud')" :active="request()->routeIs('nueva-solicitud')" icon="plus">Nueva</x-nav-button>
            @endif
            <x-nav-button :href="route('mis-tareas')" :active="request()->routeIs('mis-tareas')" icon="tasks">Mis Tareas</x-nav-button>
            <x-nav-button :href="route('equipo')" :active="request()->routeIs('equipo')" icon="users">Equipo</x-nav-button>
            @if(auth()->user()->role === 'admin')
                <x-nav-button :href="route('usuarios')" :active="request()->routeIs('usuarios')" icon="user-plus">Usuarios</x-nav-button>
            @endif
            <x-nav-button :href="route('proyectos')" :active="request()->routeIs('proyectos')" icon="folder">Proyectos</x-nav-button>
            <x-nav-button :href="route('informes')" :active="request()->routeIs('informes')" icon="chart">Informes</x-nav-button>
            <x-nav-button :href="route('archivo')" :active="request()->routeIs('archivo')" icon="archive">Archivo</x-nav-button>
        </div>

        <!-- Right: Actions & Profile -->
        <div class="flex items-center gap-2 md:gap-4">
            <button class="text-white/60 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
            </button>
            <div class="flex items-center gap-2 md:gap-3 pl-2 md:pl-4 border-l border-white/10 shrink-0">
                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-bold ring-2 ring-white/10 shadow-lg">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                
                <!-- Logout Button (Desktop) -->
                <button wire:click="logout" class="hidden md:block text-white/40 hover:text-red-400 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                </button>
            </div>

            <!-- Hamburger Button (Mobile/Tablet Only) -->
            <button @click="open = !open" class="xl:hidden text-white/60 hover:text-white transition-colors ml-2 p-1.5 bg-white/5 rounded-xl border border-white/10">
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                <svg x-show="open" style="display: none;" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
        </div>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div x-show="open" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="xl:hidden mt-3 p-3 bg-[#131a33] rounded-2xl border border-white/5 space-y-1">
        
        <x-nav-button :href="route('dashboard')" :active="request()->routeIs('dashboard')" icon="dashboard" class="w-full justify-start py-2.5">Dashboard</x-nav-button>
        @if(in_array(auth()->user()->role, ['admin', 'requester', 'employee']))
            <x-nav-button :href="route('nueva-solicitud')" :active="request()->routeIs('nueva-solicitud')" icon="plus" class="w-full justify-start py-2.5">Nueva</x-nav-button>
        @endif
        <x-nav-button :href="route('mis-tareas')" :active="request()->routeIs('mis-tareas')" icon="tasks" class="w-full justify-start py-2.5">Mis Tareas</x-nav-button>
        <x-nav-button :href="route('equipo')" :active="request()->routeIs('equipo')" icon="users" class="w-full justify-start py-2.5">Equipo</x-nav-button>
        @if(auth()->user()->role === 'admin')
            <x-nav-button :href="route('usuarios')" :active="request()->routeIs('usuarios')" icon="user-plus" class="w-full justify-start py-2.5">Usuarios</x-nav-button>
        @endif
        <x-nav-button :href="route('proyectos')" :active="request()->routeIs('proyectos')" icon="folder" class="w-full justify-start py-2.5">Proyectos</x-nav-button>
        <x-nav-button :href="route('informes')" :active="request()->routeIs('informes')" icon="chart" class="w-full justify-start py-2.5">Informes</x-nav-button>
        <x-nav-button :href="route('archivo')" :active="request()->routeIs('archivo')" icon="archive" class="w-full justify-start py-2.5">Archivo</x-nav-button>
        
        <div class="border-t border-white/5 pt-2 mt-2">
            <button wire:click="logout" class="w-full flex items-center gap-3 px-4 py-2.5 text-red-400 hover:bg-red-500/10 rounded-xl transition-all font-bold text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                Cerrar Sesión
            </button>
        </div>
    </div>
</nav>
