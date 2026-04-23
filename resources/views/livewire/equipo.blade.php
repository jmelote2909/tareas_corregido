<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-4xl font-black text-slate-900">Gestión de Equipo</h1>
            <p class="text-slate-500 mt-1">Administra tu equipo de trabajo y sus accesos al sistema</p>
        </div>
        <button wire:click="openModal" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-blue-600 hover:scale-105 active:scale-95 transition-all rounded-xl text-white font-bold shadow-lg shadow-indigo-200">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
            Agregar Miembro
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                $user = \App\Models\User::find($employee->user_id);
            @endphp
            <div class="bg-white rounded-3xl border-2 border-slate-100 shadow-xl overflow-hidden group transition-all hover:shadow-2xl">
                <div class="h-24 transition-all" style="background: linear-gradient(135deg, {{ $employee->color }}, {{ $employee->color }}dd)"></div>
                <div class="px-6 pb-6 -mt-12">
                    <div class="flex items-end justify-between mb-4">
                        <div class="h-20 w-20 rounded-2xl border-4 border-white shadow-lg overflow-hidden flex items-center justify-center text-white text-2xl font-black" style="background-color: {{ $employee->color }}">
                            @if($employee->avatar)
                                <img src="{{ $employee->avatar }}" class="w-full h-full object-cover">
                            @else
                                {{ substr($employee->name, 0, 1) }}
                            @endif
                        </div>
                        <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button wire:click="openModal('{{ $employee->id }}')" class="p-2 rounded-lg bg-slate-50 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                            </button>
                            <button wire:click="deleteEmployee('{{ $employee->id }}')" onclick="return confirm('¿Estás seguro? Esto también eliminará su usuario.')" class="p-2 rounded-lg bg-slate-50 text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </div>
                    </div>
                    
                    <h3 class="text-xl font-black text-slate-800">{{ $employee->name }}</h3>
                    <p class="text-sm text-slate-400 font-bold uppercase tracking-wider">{{ $user?->position ?? 'Sin cargo' }}</p>
                    
                    <div class="mt-2 flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span>{{ $user?->username ?? 'Sin usuario' }}</span>
                    </div>

                    <div class="flex items-center gap-2 mt-3">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $employee->role === 'Administrador' ? 'bg-indigo-100 text-indigo-600' : 'bg-slate-100 text-slate-600' }}">
                            {{ strtoupper($employee->role) }}
                        </span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $employee->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-600' }}">
                            {{ $employee->is_active ? 'ACTIVO' : 'INACTIVO' }}
                        </span>
                    </div>

                    <div class="mt-6 grid grid-cols-3 gap-2">
                        <div class="bg-amber-50 p-2 rounded-xl text-center border border-amber-100">
                            <p class="text-xs font-bold text-amber-600 uppercase">Pend</p>
                            <p class="text-lg font-black text-amber-700">{{ $stats['pendiente'] }}</p>
                        </div>
                        <div class="bg-blue-50 p-2 rounded-xl text-center border border-blue-100">
                            <p class="text-xs font-bold text-blue-600 uppercase">Proc</p>
                            <p class="text-lg font-black text-blue-700">{{ $stats['en_proceso'] }}</p>
                        </div>
                        <div class="bg-emerald-50 p-2 rounded-xl text-center border border-emerald-100">
                            <p class="text-xs font-bold text-emerald-600 uppercase">Comp</p>
                            <p class="text-lg font-black text-emerald-700">{{ $stats['completada'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-in fade-in zoom-in duration-200">
                <div class="p-6 border-b flex items-center justify-between">
                    <h3 class="text-xl font-black text-slate-800">{{ $editingEmployeeId ? 'Editar Miembro' : 'Agregar Miembro' }}</h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>
                <div class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
                    <!-- Foto -->
                    <div class="flex flex-col items-center mb-6">
                        <label class="cursor-pointer group relative">
                            <div class="h-24 w-24 rounded-3xl bg-slate-100 flex items-center justify-center text-slate-300 border-4 border-white shadow-lg overflow-hidden group-hover:scale-105 transition-all" style="background-color: {{ $color }}">
                                @if($avatar)
                                    <img src="{{ $avatar->temporaryUrl() }}" class="w-full h-full object-cover">
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                                @endif
                            </div>
                            <input type="file" wire:model="avatar" class="hidden">
                        </label>
                        <p class="text-[10px] font-bold text-slate-400 uppercase mt-2">Subir Foto de Perfil</p>
                    </div>

                    <!-- Datos Personales -->
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase">Nombre Completo</label>
                            <input type="text" wire:model="name" class="w-full h-10 bg-slate-50 border-2 border-slate-100 rounded-xl px-3 outline-none focus:border-indigo-500 transition-all">
                            @error('name') <span class="text-[10px] text-red-500 font-bold">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase">Correo</label>
                                <input type="email" wire:model="email" class="w-full h-10 bg-slate-50 border-2 border-slate-100 rounded-xl px-3 outline-none focus:border-indigo-500 transition-all">
                                @error('email') <span class="text-[10px] text-red-500 font-bold">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase">Cargo</label>
                                <input type="text" wire:model="position" placeholder="Ej: Técnico TI" class="w-full h-10 bg-slate-50 border-2 border-slate-100 rounded-xl px-3 outline-none focus:border-indigo-500 transition-all">
                            </div>
                        </div>



                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase">Departamento</label>
                                <input type="text" wire:model="department" class="w-full h-10 bg-slate-50 border-2 border-slate-100 rounded-xl px-3 outline-none focus:border-indigo-500 transition-all">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-500 uppercase">Rol Sistema</label>
                                <select wire:model="role" class="w-full h-10 bg-slate-50 border-2 border-slate-100 rounded-xl px-3 outline-none focus:border-indigo-500 transition-all">
                                    <option value="employee">Empleado</option>
                                    <option value="admin">Administrador</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase">Color Identificativo</label>
                            <div class="flex gap-2 flex-wrap mt-1">
                                @foreach(['#6366f1', '#06b6d4', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6'] as $c)
                                    <button wire:click="$set('color', '{{ $c }}')" class="h-8 w-8 rounded-full border-2 {{ $color === $c ? 'border-slate-900 scale-110 shadow-md' : 'border-transparent' }}" style="background-color: {{ $c }}"></button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <button wire:click="save" class="w-full h-12 bg-gradient-to-r from-indigo-600 to-blue-600 rounded-2xl text-white font-bold shadow-lg shadow-indigo-100 mt-6 hover:scale-[1.02] active:scale-[0.98] transition-all">
                        {{ $editingEmployeeId ? 'Guardar Cambios' : 'Crear Miembro' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
