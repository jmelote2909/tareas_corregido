<div>
    <div class="p-8 bg-[#f8f9fc] min-h-screen">
        <!-- Header Area -->
        <div class="flex items-center justify-between mb-10">
            <div>
                <h1 class="text-[32px] font-black text-[#6366f1]">Gestion de Proyectos</h1>
                <p class="text-slate-400 font-medium mt-1">Controla y da seguimiento a grandes proyectos</p>
            </div>

            <button wire:click="openModal" class="flex items-center gap-2 px-6 py-3 bg-[#8b5cf6] rounded-2xl text-white font-bold shadow-lg shadow-purple-500/20 hover:scale-105 transition-all active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                Nuevo Proyecto
            </button>
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
                    <div class="bg-white p-6 rounded-[32px] shadow-sm">
                        <h4 class="font-black text-[#1a2344] mb-2">{{ $project->name }}</h4>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-600 uppercase">{{ $project->status }}</span>
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-600 uppercase">{{ $project->priority }}</span>
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
                            <h3 class="text-2xl font-black text-[#1a2344]">Crear Nuevo Proyecto</h3>
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
                            <label class="text-xs font-black text-[#1a2344] uppercase tracking-widest mb-2 block">Responsable</label>
                            <select wire:model="responsibleId" class="w-full h-12 px-4 bg-slate-50 border-2 border-slate-50 rounded-2xl outline-none focus:border-[#8b5cf6] focus:bg-white transition-all text-[#1a2344] font-bold appearance-none cursor-pointer">
                                <option value="">Selecciona un responsable</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button wire:click="save" class="w-full h-14 bg-[#3b49ff] rounded-2xl text-white font-black text-lg shadow-xl shadow-blue-500/30 hover:scale-[1.02] active:scale-[0.98] transition-all mt-4">
                            Crear Proyecto
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
