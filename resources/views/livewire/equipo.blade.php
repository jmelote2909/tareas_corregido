<div class="container mx-auto px-4 py-8">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-10">
        <div>
            <h1 class="text-4xl font-black text-slate-900">
                @if($view === 'teams')
                    Gestión de Equipos
                @else
                    {{ $selectedTeam ? 'Equipo: ' . $selectedTeam->name : 'Mi Equipo' }}
                @endif
            </h1>
            <p class="text-slate-500 mt-1">
                @if($view === 'teams')
                    Administra los departamentos y grupos de trabajo
                @else
                    Miembros asignados a este equipo y su rendimiento
                @endif
            </p>
        </div>

        <div class="flex gap-4">
            @if(auth()->user()->role === 'admin' && $view === 'members')
                <button wire:click="backToTeams" class="inline-flex items-center gap-2 px-6 py-3 bg-white border-2 border-slate-100 hover:bg-slate-50 transition-all rounded-2xl text-slate-600 font-bold shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    Volver a Equipos
                </button>
            @endif

            @if(auth()->user()->role === 'admin')
                @if($view === 'teams')
                    <button wire:click="openTeamModal" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 hover:scale-105 active:scale-95 transition-all rounded-2xl text-white font-bold shadow-lg shadow-indigo-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                        Nuevo Equipo
                    </button>
                @elseif($selectedTeamId)
                    <button wire:click="openMemberModal" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 hover:scale-105 active:scale-95 transition-all rounded-2xl text-white font-bold shadow-lg shadow-indigo-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                        Añadir Miembro
                    </button>
                @endif
            @endif
        </div>
    </div>

    {{-- Content --}}
    @if($view === 'teams')
        {{-- Teams Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($teams as $team)
                <div class="bg-white rounded-[40px] p-8 shadow-sm border-2 border-slate-50 hover:border-indigo-100 hover:shadow-xl transition-all group relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button wire:click="openTeamModal('{{ $team->id }}')" class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                        </button>
                        <button wire:confirm="¿Seguro que quieres borrar este equipo?" wire:click="deleteTeam('{{ $team->id }}')" class="w-8 h-8 rounded-full bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                        </button>
                    </div>

                    <div class="w-16 h-16 rounded-3xl mb-6 flex items-center justify-center shadow-lg" style="background-color: {{ $team->color }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>

                    <h3 class="text-2xl font-black text-slate-800 mb-2">{{ $team->name }}</h3>
                    <p class="text-slate-400 text-sm font-medium mb-8">{{ $team->description ?? 'Sin descripción' }}</p>

                    <div class="flex items-center justify-between">
                        <div class="flex -space-x-3">
                            @foreach($employees->where('team_id', $team->id)->take(3) as $e)
                                <div class="w-10 h-10 rounded-full border-4 border-white bg-slate-100 overflow-hidden shadow-sm" style="background-color: {{ $e->color }}">
                                    @if($e->avatar)
                                        <img src="{{ $e->avatar }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="flex items-center justify-center h-full text-white text-xs font-bold">{{ substr($e->name, 0, 1) }}</span>
                                    @endif
                                </div>
                            @endforeach
                            @if($employees->where('team_id', $team->id)->count() > 3)
                                <div class="w-10 h-10 rounded-full border-4 border-white bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-400">
                                    +{{ $employees->where('team_id', $team->id)->count() - 3 }}
                                </div>
                            @endif
                        </div>

                        <button wire:click="selectTeam('{{ $team->id }}')" class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 hover:bg-indigo-600 hover:text-white transition-all flex items-center justify-center group/btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="group-hover/btn:translate-x-0.5 transition-transform"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Members Grid --}}
        @if($employees->isEmpty())
            <div class="bg-white rounded-[40px] p-20 text-center border-2 border-dashed border-slate-100">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <h3 class="text-2xl font-black text-slate-800">No hay miembros aún</h3>
                <p class="text-slate-400 mt-2">Empieza por añadir al primer miembro de este equipo</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($employees as $employee)
                    @php
                        $employeeTasks = $tasks->where('assigned_to_id', $employee->id);
                        $stats = [
                            'total' => $employeeTasks->count(),
                            'pendiente' => $employeeTasks->where('status', 'pendiente')->count(),
                            'en_proceso' => $employeeTasks->where('status', 'en_proceso')->count(),
                            'completada' => $employeeTasks->where('status', 'completada')->count(),
                        ];
                        $percent = $stats['total'] > 0 ? round(($stats['completada'] / $stats['total']) * 100) : 0;
                        $user = $employee->user;
                    @endphp
                    <div class="bg-white rounded-[40px] shadow-sm border-2 border-slate-50 overflow-hidden group hover:shadow-2xl hover:border-indigo-100 transition-all relative">
                        @if(auth()->user()->role === 'admin')
                            <div class="absolute top-0 right-0 p-4 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                                <button wire:click="openMemberModal('{{ $employee->id }}')" class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center hover:bg-white hover:text-indigo-600 transition-all shadow-sm" title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </button>
                                    <button wire:click="removeFromTeam('{{ $employee->id }}')" class="w-8 h-8 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center hover:bg-white hover:text-orange-600 transition-all shadow-sm" title="Quitar del equipo">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="18" x2="22" y1="9" y2="9"/></svg>
                                    </button>
                            </div>
                        @endif

                        <div class="h-32 relative" style="background: linear-gradient(135deg, {{ $employee->color }}, {{ $employee->color }}dd)">
                            <div class="absolute -bottom-10 left-8">
                                <div class="w-24 h-24 rounded-[32px] border-8 border-white bg-white shadow-xl overflow-hidden flex items-center justify-center text-white text-3xl font-black" style="background-color: {{ $employee->color }}">
                                    @if($employee->avatar)
                                        <img src="{{ $employee->avatar }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr($employee->name, 0, 1) }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="px-8 pt-14 pb-8">
                            <h3 class="text-2xl font-black text-slate-800 leading-tight mb-1">{{ $employee->name }}</h3>
                            <p class="text-sm text-slate-400 font-bold uppercase tracking-widest">{{ $user?->position ?? 'Sin cargo' }}</p>

                            <div class="flex items-center gap-2 mt-4 mb-8">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black {{ $employee->role === 'Administrador' ? 'bg-indigo-50 text-indigo-600' : 'bg-slate-50 text-slate-400' }}">
                                    {{ strtoupper($employee->role) }}
                                </span>
                                <span class="px-3 py-1 rounded-full text-[10px] font-black {{ $employee->is_active ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-50 text-slate-400' }}">
                                    {{ $employee->is_active ? 'ACTIVO' : 'INACTIVO' }}
                                </span>
                            </div>

                            <div class="grid grid-cols-3 gap-4 mb-8">
                                <div class="bg-slate-50 p-4 rounded-3xl text-center">
                                    <p class="text-lg font-black text-slate-700 leading-none">{{ $stats['pendiente'] }}</p>
                                    <p class="text-[9px] font-black text-slate-400 uppercase mt-1">Pend</p>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-3xl text-center">
                                    <p class="text-lg font-black text-slate-700 leading-none">{{ $stats['en_proceso'] }}</p>
                                    <p class="text-[9px] font-black text-slate-400 uppercase mt-1">Proc</p>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-3xl text-center">
                                    <p class="text-lg font-black text-slate-700 leading-none">{{ $stats['completada'] }}</p>
                                    <p class="text-[9px] font-black text-slate-400 uppercase mt-1">Comp</p>
                                </div>
                            </div>

                            @if($stats['total'] > 0)
                                <div class="space-y-2">
                                    <div class="flex justify-between text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        <span>Rendimiento</span>
                                        <span>{{ $percent }}%</span>
                                    </div>
                                    <div class="h-3 bg-slate-50 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-1000 shadow-sm shadow-black/10" style="width: {{ $percent }}%; background-color: {{ $employee->color }}"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endif

    {{-- Modals --}}
    @if($showTeamModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-md">
            <div class="bg-white rounded-[40px] shadow-2xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-300">
                <div class="p-10 border-b border-slate-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-3xl font-black text-slate-800">{{ $teamId ? 'Editar Equipo' : 'Nuevo Equipo' }}</h3>
                        <p class="text-slate-400 font-medium">Define los detalles de tu departamento</p>
                    </div>
                    <button wire:click="$set('showTeamModal', false)" class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>
                <div class="p-10 space-y-8">
                    <div>
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3 block">Nombre del Equipo</label>
                        <input type="text" wire:model="teamName" placeholder="Ej: Infraestructura y TI" class="w-full h-14 bg-slate-50 border-2 border-slate-50 rounded-2xl px-6 outline-none focus:border-indigo-500 focus:bg-white transition-all text-slate-800 font-bold">
                        @error('teamName') <span class="text-xs text-red-500 font-bold mt-2 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3 block">Descripción</label>
                        <textarea wire:model="teamDescription" rows="3" placeholder="¿A qué se dedica este equipo?" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl p-6 outline-none focus:border-indigo-500 focus:bg-white transition-all text-slate-800 font-bold"></textarea>
                    </div>

                    <div>
                        <label class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3 block">Color del Equipo</label>
                        <div class="flex gap-3 flex-wrap">
                            @foreach(['#6366f1', '#06b6d4', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6'] as $c)
                                <button wire:click="$set('teamColor', '{{ $c }}')" class="h-10 w-10 rounded-2xl border-4 {{ $teamColor === $c ? 'border-slate-800 scale-110 shadow-lg' : 'border-transparent' }} transition-all" style="background-color: {{ $c }}"></button>
                            @endforeach
                        </div>
                    </div>

                    <button wire:click="saveTeam" class="w-full h-16 bg-gradient-to-r from-indigo-600 to-blue-600 rounded-3xl text-white font-black text-lg shadow-xl shadow-indigo-100 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        {{ $teamId ? 'Actualizar Equipo' : 'Crear Equipo' }}
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if($showMemberModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-md">
            <div class="bg-white rounded-[40px] shadow-2xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-300">
                <div class="p-10 border-b border-slate-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-3xl font-black text-slate-800">{{ $editingEmployeeId ? 'Editar Miembro' : 'Nuevo Miembro' }}</h3>
                        <p class="text-slate-400 font-medium">Información personal y accesos</p>
                    </div>
                    <button wire:click="$set('showMemberModal', false)" class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>
                <div class="p-10 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                    @if(!$editingEmployeeId)
                        {{-- Add Member Mode: Only search and selection --}}
                        <div>
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3 block">Buscar Usuario para Añadir</label>
                            <div class="relative">
                                <input type="text" wire:model.live="userSearch" placeholder="Escribe un nombre o correo..." class="w-full h-14 bg-slate-50 border-2 border-slate-50 rounded-2xl px-12 outline-none focus:border-indigo-500 focus:bg-white transition-all text-slate-800 font-bold">
                                <svg class="absolute left-4 top-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1 block">Resultados de Búsqueda</label>
                            @forelse($availableUsers as $user)
                                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border-2 border-transparent hover:border-indigo-100 transition-all group/item">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white text-xl font-black shadow-lg shadow-indigo-100">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800 leading-none">{{ $user->name }}</p>
                                            <p class="text-xs text-slate-400 mt-1 font-medium">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <button wire:click="addToTeam('{{ $user->id }}')" class="px-6 py-2.5 bg-white border-2 border-slate-100 hover:bg-indigo-600 hover:border-indigo-600 hover:text-white transition-all rounded-xl text-xs font-black text-slate-600 shadow-sm">
                                        Añadir al Equipo
                                    </button>
                                </div>
                            @empty
                                <div class="text-center py-12 bg-slate-50 rounded-[32px] border-2 border-dashed border-slate-100">
                                    <svg class="mx-auto text-slate-200 mb-4" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m16 16-4-4-4 4"/><path d="m8 8 4 4 4-4"/></svg>
                                    <p class="text-slate-400 font-bold">No se encontraron usuarios disponibles</p>
                                    <p class="text-[10px] text-slate-300 uppercase tracking-widest mt-1">Intenta con otro nombre</p>
                                </div>
                            @endforelse
                        </div>
                    @else
                        {{-- Edit Member Mode: Only form --}}
                        {{-- Avatar --}}
                        <div class="flex flex-col items-center mb-8">
                            <label class="cursor-pointer group relative">
                                <div class="h-32 w-32 rounded-[40px] bg-slate-50 flex items-center justify-center text-slate-300 border-4 border-white shadow-xl overflow-hidden group-hover:scale-105 transition-all" style="background-color: {{ $color }}">
                                    @if($avatar)
                                        <img src="{{ $avatar->temporaryUrl() }}" class="w-full h-full object-cover">
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                                    @endif
                                </div>
                                <input type="file" wire:model="avatar" class="hidden">
                            </label>
                            <p class="text-xs font-black text-slate-400 uppercase mt-4 tracking-widest">Subir Foto</p>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2 block">Nombre Completo</label>
                                <input type="text" wire:model="name" class="w-full h-12 bg-slate-50 border-2 border-slate-50 rounded-2xl px-4 outline-none focus:border-indigo-500 focus:bg-white transition-all text-slate-800 font-bold">
                                @error('name') <span class="text-xs text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2 block">Usuario</label>
                                <input type="text" wire:model="username" class="w-full h-12 bg-slate-50 border-2 border-slate-50 rounded-2xl px-4 outline-none focus:border-indigo-500 focus:bg-white transition-all text-slate-800 font-bold">
                            </div>
                            <div>
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2 block">Contraseña</label>
                                <input type="password" wire:model="password" placeholder="••••••••" class="w-full h-12 bg-slate-50 border-2 border-slate-50 rounded-2xl px-4 outline-none focus:border-indigo-500 focus:bg-white transition-all text-slate-800 font-bold">
                                @error('password') <span class="text-xs text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-span-2">
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2 block">Correo Electrónico</label>
                                <input type="email" wire:model="email" class="w-full h-12 bg-slate-50 border-2 border-slate-50 rounded-2xl px-4 outline-none focus:border-indigo-500 focus:bg-white transition-all text-slate-800 font-bold">
                                @error('email') <span class="text-xs text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2 block">Cargo</label>
                                <input type="text" wire:model="position" class="w-full h-12 bg-slate-50 border-2 border-slate-50 rounded-2xl px-4 outline-none focus:border-indigo-500 focus:bg-white transition-all text-slate-800 font-bold">
                            </div>
                            <div>
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2 block">Rol Sistema</label>
                                <select wire:model="role" class="w-full h-12 bg-slate-50 border-2 border-slate-50 rounded-2xl px-4 outline-none focus:border-indigo-500 focus:bg-white transition-all text-slate-800 font-bold appearance-none">
                                    <option value="employee">Empleado</option>
                                    <option value="admin">Administrador</option>
                                    <option value="requester">Solicitante</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3 block">Color Personal</label>
                            <div class="flex gap-2 flex-wrap">
                                @foreach(['#6366f1', '#06b6d4', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6'] as $c)
                                    <button wire:click="$set('color', '{{ $c }}')" class="h-8 w-8 rounded-xl border-4 {{ $color === $c ? 'border-slate-800 scale-110' : 'border-transparent' }} transition-all" style="background-color: {{ $c }}"></button>
                                @endforeach
                            </div>
                        </div>

                        <button wire:click="saveMember" class="w-full h-16 bg-gradient-to-r from-indigo-600 to-blue-600 rounded-3xl text-white font-black text-lg shadow-xl shadow-indigo-100 mt-6 hover:scale-[1.02] active:scale-[0.98] transition-all">
                            Guardar Cambios
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

