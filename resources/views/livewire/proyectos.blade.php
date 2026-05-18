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
            <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-md" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; display: flex; align-items: center; justify-content: center; z-index: 100;">
                <div class="bg-white rounded-[40px] shadow-2xl w-full {{ $projectId ? 'max-w-6xl' : 'max-w-2xl' }} animate-in fade-in zoom-in duration-300" style="display: flex; flex-direction: column; max-height: 85vh; {{ $projectId ? 'height: 85vh;' : '' }} overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
                    <div class="p-8 border-b border-slate-100 flex items-center justify-between shrink-0" style="display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; padding: 2rem; border-bottom: 1px solid #f1f5f9;">
                        <div>
                            <h3 class="text-2xl font-black text-[#1a2344]">{{ $projectId ? 'Editar Proyecto' : 'Crear Nuevo Proyecto' }}</h3>
                            <p class="text-sm text-slate-400 font-medium">Completa la información del proyecto</p>
                        </div>
                        <button wire:click="$set('showModal', false)" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        </button>
                    </div>

                    <div style="display: flex; flex: 1 1 0%; min-height: 0; overflow: hidden;">
                        <div class="p-8 space-y-6 w-full custom-scrollbar {{ $projectId ? 'border-r border-slate-100' : '' }}" style="flex: 1; overflow-y: auto; min-height: 0; padding: 2rem;">
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

                        @if($projectId)
                            <div style="width: 450px; display: flex; flex-direction: column; height: 100%; min-height: 0; overflow: hidden;" class="bg-slate-50">
                                <div class="p-6 border-b border-slate-200 bg-white shrink-0">
                                    <h4 class="font-black text-[#1a2344] flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-[#8b5cf6]"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/></svg>
                                        Historial de Actividad
                                    </h4>
                                    <p class="text-[11px] text-slate-400 font-medium mt-1">Cambios y comentarios del proyecto</p>
                                </div>
                                <div class="custom-scrollbar space-y-4" style="flex: 1 1 0%; overflow-y: auto; min-height: 0; padding: 1.5rem;">
                                    @forelse($histories as $history)
                                        @if($history->type === 'system')
                                            <div class="flex items-center justify-center">
                                                <div class="bg-indigo-50 text-indigo-700 text-[10px] font-bold px-4 py-2 rounded-full border border-indigo-100/50 shadow-sm">
                                                    {{ $history->text }} - <span class="opacity-70">{{ $history->created_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex gap-4 p-4 bg-white rounded-3xl shadow-sm border border-slate-100">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-sm shrink-0 shadow-md">
                                                    {{ substr($history->user_name, 0, 1) }}
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex justify-between items-center mb-1">
                                                        <span class="text-sm font-black text-[#1a2344]">{{ $history->user_name }}</span>
                                                        <span class="text-[10px] text-slate-400 font-bold">{{ $history->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-sm text-slate-600 font-medium">{{ $history->text }}</p>
                                                    @if($history->image_path)
                                                        <img src="{{ asset('storage/' . $history->image_path) }}" style="max-width: 100%; height: auto; max-height: 250px; border-radius: 1rem; margin-top: 0.75rem; object-fit: cover;" class="border border-slate-200 cursor-pointer shadow-sm" onclick="window.open(this.src, '_blank')">
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @empty
                                        <div class="text-center py-10">
                                            <p class="text-sm font-medium text-slate-400">Aún no hay actividad en este proyecto.</p>
                                        </div>
                                    @endforelse
                                </div>
                                <div class="p-6 bg-white border-t border-slate-200 shrink-0">
                                    <form wire:submit.prevent="addHistoryComment" class="space-y-3">
                                        <div class="relative">
                                            <textarea wire:model="newHistoryText" rows="2" class="w-full bg-slate-50 border-2 border-slate-100 rounded-2xl px-4 py-3 outline-none focus:border-[#8b5cf6] focus:bg-white transition-all text-sm font-medium placeholder:text-slate-400 resize-none" placeholder="Escribe un comentario..."></textarea>
                                        </div>
                                        <div class="flex items-center justify-between gap-4">
                                            <label class="cursor-pointer group flex items-center gap-2">
                                                <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                                </div>
                                                <span class="text-xs font-bold text-slate-400 group-hover:text-indigo-600">Subir Imagen</span>
                                                <input type="file" wire:model="newHistoryImage" accept="image/*" class="hidden">
                                            </label>
                                            
                                            <button type="submit" class="px-6 py-2 bg-[#8b5cf6] rounded-xl text-white font-bold text-sm shadow-lg shadow-purple-500/20 hover:scale-105 transition-all">
                                                Enviar
                                            </button>
                                        </div>
                                        @if($newHistoryImage)
                                            <div class="text-[10px] font-bold text-emerald-600 flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                                Imagen cargada lista para enviar
                                            </div>
                                        @endif
                                        @error('newHistoryText') <span class="text-[10px] text-red-500 font-bold">{{ $message }}</span> @enderror
                                    </form>
                                </div>
                            </div>
                        @endif
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
