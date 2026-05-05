<div>
    <div class="p-8 bg-[#f8f9fc] min-h-screen">
        <!-- Header Area -->
        <div class="flex items-center justify-between mb-10">
            <div>
                <h1 class="text-[32px] font-black text-[#6366f1]">Gestion de Proyectos</h1>
                <p class="text-slate-400 font-medium mt-1">Controla y da seguimiento a grandes proyectos</p>
            </div>

            @if(auth()->user()->role === 'admin')
                <button wire:click="openModal" class="flex items-center gap-2 px-6 py-3 bg-[#8b5cf6] rounded-2xl text-white font-bold shadow-lg shadow-purple-500/20 hover:scale-105 transition-all active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    Nuevo Proyecto
                </button>
            @endif
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-4 gap-6 mb-10">
            <!-- Total -->
            <div class="bg-[#f3f0ff] rounded-[32px] p-8 shadow-sm group hover:shadow-md transition-all border-2 border-transparent hover:border-[#8b5cf6]/20">
                <p class="text-[11px] font-black text-[#8b5cf6] uppercase tracking-widest mb-4">Total Proyectos</p>
                <p class="text-[44px] font-black text-[#1a2344] leading-tight">{{ $stats['total'] }}</p>
            </div>

            <!-- Planificación -->
            <div class="bg-[#fffbeb] rounded-[32px] p-8 shadow-sm group hover:shadow-md transition-all border-2 border-transparent hover:border-[#f59e0b]/20">
                <p class="text-[11px] font-black text-[#f59e0b] uppercase tracking-widest mb-4">Planificación</p>
                <p class="text-[44px] font-black text-[#1a2344] leading-tight">{{ $stats['planificacion'] }}</p>
            </div>

            <!-- En Progreso -->
            <div class="bg-[#eff6ff] rounded-[32px] p-8 shadow-sm group hover:shadow-md transition-all border-2 border-transparent hover:border-[#3b82f6]/20">
                <p class="text-[11px] font-black text-[#3b82f6] uppercase tracking-widest mb-4">En Progreso</p>
                <p class="text-[44px] font-black text-[#1a2344] leading-tight">{{ $stats['en_progreso'] }}</p>
            </div>

            <!-- Completados -->
            <div class="bg-[#f0fdf4] rounded-[32px] p-8 shadow-sm group hover:shadow-md transition-all border-2 border-transparent hover:border-[#10b981]/20">
                <p class="text-[11px] font-black text-[#10b981] uppercase tracking-widest mb-4">Completados</p>
                <p class="text-[44px] font-black text-[#1a2344] leading-tight">{{ $stats['completados'] }}</p>
            </div>
        </div>

        <!-- Empty State / List Area -->
        @if($projects->isEmpty())
            <div class="bg-white rounded-[40px] shadow-sm border-2 border-dashed border-[#8b5cf6]/20 p-20 flex flex-col items-center justify-center">
                <div class="w-16 h-16 rounded-2xl bg-[#f3f0ff] flex items-center justify-center text-[#8b5cf6] mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/></svg>
                </div>
                <h3 class="text-xl font-black text-[#1a2344] mb-2">Sin proyectos</h3>
                <p class="text-slate-400 font-medium text-center">Crea tu primer proyecto para comenzar</p>
                <button wire:click="openModal" class="mt-6 text-[#8b5cf6] font-bold hover:underline">Comenzar ahora</button>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($projects as $project)
                    <div class="bg-white p-8 rounded-[32px] shadow-sm relative group">
                        @if(auth()->user()->role === 'admin')
                            <div class="absolute top-6 right-6 flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-all">
                                <button wire:click="openModal('{{ $project->id }}')" class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-100 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                </button>
                                <button wire:confirm="¿Estás seguro de eliminar este proyecto?" wire:click="delete('{{ $project->id }}')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-100 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </button>
                            </div>
                        @endif

                        <h4 class="font-black text-[#1a2344] text-lg mb-2">{{ $project->name }}</h4>
                        <p class="text-xs text-slate-400 font-medium mb-6 line-clamp-2">{{ $project->description }}</p>
                        
                        <div class="flex items-center gap-2 mb-6">
                            @php
                                $statusColors = [
                                    'Planificación' => 'bg-amber-100 text-amber-600',
                                    'En Progreso' => 'bg-blue-100 text-blue-600',
                                    'Detenido' => 'bg-red-100 text-red-600',
                                    'Completado' => 'bg-emerald-100 text-emerald-600',
                                ];
                                $priorityColors = [
                                    'Baja' => 'bg-slate-100 text-slate-600',
                                    'Media' => 'bg-indigo-100 text-indigo-600',
                                    'Alta' => 'bg-orange-100 text-orange-600',
                                    'Crítica' => 'bg-red-100 text-red-600',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $statusColors[$project->status] ?? 'bg-slate-100 text-slate-600' }}">{{ $project->status }}</span>
                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $priorityColors[$project->priority] ?? 'bg-slate-100 text-slate-600' }}">{{ $project->priority }}</span>
                        </div>

                        <div class="pt-6 border-t border-slate-50 flex items-center justify-between">
                            <div class="flex -space-x-2">
                                @foreach($project->users as $user)
                                    <div class="w-8 h-8 rounded-full border-2 border-white bg-indigo-500 flex items-center justify-center text-[10px] font-bold text-white shadow-sm" title="{{ $user->name }}">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endforeach
                                @if($project->users->isEmpty())
                                    <span class="text-[10px] text-slate-300 font-bold italic">Sin asignar</span>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">Presupuesto</p>
                                <p class="text-sm font-black text-[#1a2344]">${{ number_format($project->budget, 0) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Modal: Crear Nuevo Proyecto --}}
        @if($showModal)
            <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-md">
                <div class="bg-white rounded-[40px] shadow-2xl w-full max-w-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
                    <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-black text-[#1a2344]">{{ $projectId ? 'Editar Proyecto' : 'Crear Nuevo Proyecto' }}</h3>
                            <p class="text-sm text-slate-400 font-medium">Completa la información del proyecto</p>
                        </div>
                        <button wire:click="$set('showModal', false)" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        </button>
                    </div>

                    <div class="p-8 space-y-6 max-h-[75vh] overflow-y-auto custom-scrollbar">
                        <div>
                            <label class="text-xs font-black text-[#1a2344] uppercase tracking-widest mb-2 block">Nombre del Proyecto</label>
                            <input type="text" wire:model="name" placeholder="Ej: Rediseño de Red Local" class="w-full h-12 bg-slate-50 border-2 border-slate-50 rounded-2xl px-4 outline-none focus:border-[#8b5cf6] focus:bg-white transition-all text-[#1a2344] font-bold">
                            @error('name') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-black text-[#1a2344] uppercase tracking-widest mb-2 block">Descripción</label>
                            <textarea wire:model="description" rows="3" class="w-full bg-slate-50 border-2 border-slate-50 rounded-2xl px-4 py-3 outline-none focus:border-[#8b5cf6] focus:bg-white transition-all text-[#1a2344] font-bold placeholder:text-slate-300" placeholder="Detalla los objetivos del proyecto..."></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="text-xs font-black text-[#1a2344] uppercase tracking-widest mb-2 block">Estado</label>
                                <select wire:model="status" class="w-full h-12 px-4 bg-slate-50 border-2 border-slate-50 rounded-2xl outline-none focus:border-[#8b5cf6] focus:bg-white transition-all text-[#1a2344] font-bold appearance-none cursor-pointer">
                                    <option value="Planificación">Planificación</option>
                                    <option value="En Progreso">En Progreso</option>
                                    <option value="Detenido">Detenido</option>
                                    <option value="Completado">Completado</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-black text-[#1a2344] uppercase tracking-widest mb-2 block">Prioridad</label>
                                <select wire:model="priority" class="w-full h-12 px-4 bg-slate-50 border-2 border-slate-50 rounded-2xl outline-none focus:border-[#8b5cf6] focus:bg-white transition-all text-[#1a2344] font-bold appearance-none cursor-pointer">
                                    <option value="Baja">Baja</option>
                                    <option value="Media">Media</option>
                                    <option value="Alta">Alta</option>
                                    <option value="Crítica">Crítica</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="text-xs font-black text-[#1a2344] uppercase tracking-widest mb-2 block">Fecha de Inicio</label>
                                <input type="date" wire:model="startDate" class="w-full h-12 bg-slate-50 border-2 border-slate-50 rounded-2xl px-4 outline-none focus:border-[#8b5cf6] focus:bg-white transition-all text-[#1a2344] font-bold">
                            </div>
                            <div>
                                <label class="text-xs font-black text-[#1a2344] uppercase tracking-widest mb-2 block">Fecha de Fin (Opcional)</label>
                                <input type="date" wire:model="endDate" class="w-full h-12 bg-slate-50 border-2 border-slate-50 rounded-2xl px-4 outline-none focus:border-[#8b5cf6] focus:bg-white transition-all text-[#1a2344] font-bold">
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-black text-[#1a2344] uppercase tracking-widest mb-2 block">Presupuesto (Opcional)</label>
                            <div class="relative">
                                <span class="absolute left-4 inset-y-0 flex items-center text-slate-400 font-bold">$</span>
                                <input type="number" wire:model="budget" placeholder="0.00" class="w-full h-12 pl-8 pr-4 bg-slate-50 border-2 border-slate-50 rounded-2xl outline-none focus:border-[#8b5cf6] focus:bg-white transition-all text-[#1a2344] font-bold">
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-black text-[#1a2344] uppercase tracking-widest mb-4 block">Equipo Asignado (Usuarios)</label>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($users as $user)
                                    <label class="flex items-center gap-3 p-3 rounded-2xl border-2 transition-all cursor-pointer {{ in_array($user->id, $selectedUsers) ? 'bg-indigo-50 border-indigo-200 shadow-sm' : 'bg-slate-50 border-transparent hover:border-slate-200' }}">
                                        <input type="checkbox" wire:model.live="selectedUsers" value="{{ $user->id }}" class="hidden">
                                        <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-[10px] font-bold text-white shadow-sm">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-slate-700 leading-tight">{{ $user->name }}</span>
                                            <span class="text-[9px] text-slate-400 font-medium">{{ $user->position }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <button wire:click="save" class="w-full h-14 bg-[#3b49ff] rounded-2xl text-white font-black text-lg shadow-xl shadow-blue-500/30 hover:scale-[1.02] active:scale-[0.98] transition-all mt-4">
                            {{ $projectId ? 'Guardar Cambios' : 'Crear Proyecto' }}
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
</div>
