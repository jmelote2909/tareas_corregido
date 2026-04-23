<div class="p-8 bg-[#f8f9fc] min-h-screen">
    <!-- Header Area -->
    <div class="flex items-center justify-between mb-10">
        <div>
            <h1 class="text-[32px] font-black text-[#1a2344]">Gestion de Usuarios</h1>
            <p class="text-slate-400 font-medium mt-1">Administra los usuarios del sistema</p>
        </div>

        <button wire:click="openModal" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#4353ff] to-[#6366f1] rounded-2xl text-white font-bold shadow-lg shadow-blue-500/20 hover:scale-105 transition-all active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-3-3.87"/><path d="M11 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6"/><path d="M16 11h6"/></svg>
            Nuevo Usuario
        </button>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-5 gap-6 mb-10">
        <!-- Total -->
        <div class="bg-white rounded-[32px] p-6 shadow-sm flex items-center gap-4 group hover:shadow-md transition-all">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-500 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div>
                <p class="text-2xl font-black text-[#1a2344] leading-tight">{{ $stats['total'] }}</p>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total</p>
            </div>
        </div>

        <!-- Activos -->
        <div class="bg-white rounded-[32px] p-6 shadow-sm flex items-center gap-4 group hover:shadow-md transition-all">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div>
                <p class="text-2xl font-black text-[#1a2344] leading-tight">{{ $stats['activos'] }}</p>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Activos</p>
            </div>
        </div>

        <!-- Admins -->
        <div class="bg-white rounded-[32px] p-6 shadow-sm flex items-center gap-4 group hover:shadow-md transition-all">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/></svg>
            </div>
            <div>
                <p class="text-2xl font-black text-[#1a2344] leading-tight">{{ $stats['admins'] }}</p>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Admins</p>
            </div>
        </div>

        <!-- Empleados -->
        <div class="bg-white rounded-[32px] p-6 shadow-sm flex items-center gap-4 group hover:shadow-md transition-all">
            <div class="w-12 h-12 rounded-2xl bg-cyan-50 flex items-center justify-center text-cyan-500 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-black text-[#1a2344] leading-tight">{{ $stats['empleados'] }}</p>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Empleados</p>
            </div>
        </div>

        <!-- Solicitantes -->
        <div class="bg-white rounded-[32px] p-6 shadow-sm flex items-center gap-4 group hover:shadow-md transition-all">
            <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-500 group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
            </div>
            <div>
                <p class="text-2xl font-black text-[#1a2344] leading-tight">{{ $stats['solicitantes'] }}</p>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Solicitantes</p>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="max-w-md relative mb-10">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
        </div>
        <input wire:model.live="searchQuery" type="text" placeholder="Buscar usuarios..." 
            class="w-full pl-11 pr-4 py-3 bg-white border-0 rounded-2xl shadow-sm focus:ring-2 focus:ring-blue-500/10 outline-none transition-all text-[#1a2344] font-medium placeholder:text-slate-300">
    </div>

    {{-- Modal: Crear Nuevo Usuario --}}
    @if($showModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-md">
            <div class="bg-white rounded-[40px] shadow-2xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-300">
                <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-black text-[#1a2344]">Crear Nuevo Usuario</h3>
                        <p class="text-sm text-slate-400 font-medium">Completa la información del nuevo usuario</p>
                    </div>
                    <button wire:click="$set('showModal', false)" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>

                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="text-xs font-black text-[#1a2344] uppercase tracking-widest mb-2 block">Usuario</label>
                            <input type="text" wire:model="username" placeholder="nombre.usuario" class="w-full h-12 bg-slate-50 border-2 border-slate-50 rounded-2xl px-4 outline-none focus:border-blue-500 focus:bg-white transition-all text-[#1a2344] font-bold">
                            @error('username') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-black text-[#1a2344] uppercase tracking-widest mb-2 block">Contraseña</label>
                            <input type="password" wire:model="password" placeholder="••••••••" class="w-full h-12 bg-slate-50 border-2 border-slate-50 rounded-2xl px-4 outline-none focus:border-blue-500 focus:bg-white transition-all text-[#1a2344] font-bold">
                            @error('password') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-black text-[#1a2344] uppercase tracking-widest mb-2 block">Nombre Completo</label>
                        <input type="text" wire:model="name" placeholder="Juan Perez" class="w-full h-12 bg-slate-50 border-2 border-slate-50 rounded-2xl px-4 outline-none focus:border-blue-500 focus:bg-white transition-all text-[#1a2344] font-bold">
                        @error('name') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-black text-[#1a2344] uppercase tracking-widest mb-2 block">Email</label>
                        <input type="email" wire:model="email" placeholder="usuario@empresa.com" class="w-full h-12 bg-slate-50 border-2 border-slate-50 rounded-2xl px-4 outline-none focus:border-blue-500 focus:bg-white transition-all text-[#1a2344] font-bold">
                        @error('email') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-black text-[#1a2344] uppercase tracking-widest mb-2 block">Departamento</label>
                        <input type="text" wire:model="department" class="w-full h-12 bg-slate-50 border-2 border-slate-50 rounded-2xl px-4 outline-none focus:border-blue-500 focus:bg-white transition-all text-[#1a2344] font-bold">
                    </div>

                    <div>
                        <label class="text-xs font-black text-[#1a2344] uppercase tracking-widest mb-2 block">Rol</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                            </div>
                            <select wire:model="role" class="w-full h-12 pl-12 pr-4 bg-slate-50 border-2 border-slate-50 rounded-2xl outline-none focus:border-blue-500 focus:bg-white transition-all text-[#1a2344] font-bold appearance-none cursor-pointer">
                                <option value="employee">Empleado</option>
                                <option value="admin">Administrador</option>
                                <option value="requester">Solicitante</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </div>
                        </div>
                    </div>

                    <button wire:click="save" class="w-full h-14 bg-[#3b49ff] rounded-2xl text-white font-black text-lg shadow-xl shadow-blue-500/30 hover:scale-[1.02] active:scale-[0.98] transition-all mt-4">
                        Crear Usuario
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
