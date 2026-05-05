@php
    $getStatusColor = function($s) {
        return match($s) {
            'completada' => 'bg-emerald-500 text-white',
            'en_proceso' => 'bg-blue-500 text-white',
            'pendiente' => 'bg-amber-500 text-white',
            'cancelada' => 'bg-gray-400 text-white',
            default => 'bg-gray-100 text-gray-800'
        };
    };
    $getPriorityColor = function($p) {
        return match($p) {
            'urgente' => 'bg-red-500 text-white',
            'alta' => 'bg-orange-500 text-white',
            'media' => 'bg-blue-500 text-white',
            'baja' => 'bg-gray-400 text-white',
            default => 'bg-gray-400 text-white'
        };
    };
    $empColor = $task->assignedTo->color ?? '#6366f1';
@endphp

<div class="container mx-auto px-4 py-8">
    <a href="{{ route('dashboard') }}" wire:navigate class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
        Volver al Dashboard
    </a>

    {{-- Task Header Bar --}}
    <div class="rounded-xl p-6 mb-6 text-white shadow-lg" style="background: linear-gradient(135deg, {{ $empColor }}, {{ $empColor }}dd)">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $getStatusColor($task->status) }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $getPriorityColor($task->priority) }}">{{ ucfirst($task->priority) }}</span>
                    @if($task->assignedTo)
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-white/20 text-white border border-white/30">{{ $task->assignedTo->name }}</span>
                    @endif
                </div>
                <h1 class="text-2xl font-black">{{ $task->title }}</h1>
                <p class="text-white/70 text-sm mt-1">Solicitud de {{ $task->requestedBy->name ?? 'N/A' }} - {{ $task->requestedBy->department ?? 'N/A' }}</p>
            </div>
            <div class="flex gap-2">
                @if($isAdmin || $task->requested_by_id === auth()->id())
                    @if($isEditing)
                        <button wire:click="saveEdit" class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-bold bg-white/20 hover:bg-white/30 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Guardar
                        </button>
                    @else
                        <button wire:click="$set('isEditing', true)" class="p-2 rounded-lg hover:bg-white/20 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Description --}}
            <div class="bg-white rounded-2xl border-2 border-slate-100 shadow-lg p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Descripción</h3>
                @if($isEditing)
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase">Título</label>
                            <input type="text" wire:model="editTitle" class="w-full bg-slate-50 border-2 border-slate-100 rounded-lg h-10 px-3 mt-1">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase">Descripción</label>
                            <textarea wire:model="editDescription" rows="5" class="w-full bg-slate-50 border-2 border-slate-100 rounded-lg p-3 mt-1"></textarea>
                        </div>
                    </div>
                @else
                    <p class="text-slate-600 whitespace-pre-wrap">{{ $task->description }}</p>
                @endif
            </div>

            {{-- Comments --}}
            <div class="bg-white rounded-2xl border-2 border-slate-100 shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-cyan-50 to-blue-50 p-4 border-b-2 border-slate-100 flex items-center gap-2">
                    <div class="p-2 bg-cyan-100 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan-600"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </div>
                    <h3 class="font-bold text-slate-800">Comentarios</h3>
                    <span class="bg-cyan-100 text-cyan-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ $task->comments->count() }}</span>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($task->comments as $comment)
                        <div class="flex gap-4">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 flex items-center justify-center text-white font-bold flex-shrink-0">
                                {{ substr($comment->user_name, 0, 1) }}
                            </div>
                            <div class="flex-1 bg-slate-50 rounded-2xl p-4">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="font-bold text-sm text-slate-800">{{ $comment->user_name }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-slate-600">{{ $comment->text }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-slate-400 italic py-4">No hay comentarios todavía</p>
                    @endforelse

                    <div class="pt-4 border-t border-slate-100 flex gap-3">
                        <textarea wire:model="newComment" placeholder="Escribe un comentario..." rows="2" class="flex-1 bg-slate-50 border-2 border-slate-100 rounded-xl p-3 text-sm focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition-all"></textarea>
                        <button wire:click="addComment" class="px-6 bg-gradient-to-r from-cyan-500 to-blue-500 text-white font-bold rounded-xl shadow-md hover:scale-[1.02] active:scale-[0.98] transition-all">
                            Enviar
                        </button>
                    </div>
                </div>
            </div>
            {{-- Attachments --}}
            @if($task->attachments->count() > 0)
                <div class="bg-white rounded-2xl border-2 border-slate-100 shadow-lg p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-500"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.51a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                        Archivos Adjuntos
                    </h3>
                    
                    <div class="space-y-6">
                        {{-- Images Grid --}}
                        @php $images = $task->attachments->where('type', 'image'); @endphp
                        @if($images->count() > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                @foreach($images as $img)
                                    <a href="{{ $img->url }}" target="_blank" class="group relative aspect-square rounded-xl overflow-hidden border-2 border-slate-50 hover:border-indigo-200 transition-all shadow-sm">
                                        <img src="{{ $img->url }}" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 flex items-center justify-center transition-all opacity-0 group-hover:opacity-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        {{-- Audio Player --}}
                        @php $audios = $task->attachments->where('type', 'audio'); @endphp
                        @if($audios->count() > 0)
                            <div class="space-y-3">
                                @foreach($audios as $audio)
                                    <div class="bg-indigo-50 rounded-2xl p-4 border-2 border-indigo-100">
                                        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v1a7 7 0 0 1-14 0v-1"/><line x1="12" x2="12" y1="19" y2="22"/></svg>
                                            Nota de voz adjunta
                                        </p>
                                        <audio controls class="w-full h-10">
                                            <source src="{{ $audio->url }}">
                                            Tu navegador no soporta el elemento de audio.
                                        </audio>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            @if($isAdmin)
                <div class="bg-white rounded-2xl border-2 border-slate-100 shadow-lg p-6 space-y-4">
                    <h3 class="font-bold text-slate-800 text-lg border-b pb-2">Acciones</h3>
                    
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-400 uppercase">Estado</label>
                        <select wire:change="updateStatus($event.target.value)" class="w-full bg-slate-50 border-2 border-slate-100 rounded-lg h-10 px-3">
                            <option value="pendiente" {{ $task->status === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="en_proceso" {{ $task->status === 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                            <option value="completada" {{ $task->status === 'completada' ? 'selected' : '' }}>Completada</option>
                            <option value="cancelada" {{ $task->status === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-400 uppercase">Prioridad</label>
                        <select wire:change="updatePriority($event.target.value)" class="w-full bg-slate-50 border-2 border-slate-100 rounded-lg h-10 px-3">
                            <option value="baja" {{ $task->priority === 'baja' ? 'selected' : '' }}>Baja</option>
                            <option value="media" {{ $task->priority === 'media' ? 'selected' : '' }}>Media</option>
                            <option value="alta" {{ $task->priority === 'alta' ? 'selected' : '' }}>Alta</option>
                            <option value="urgente" {{ $task->priority === 'urgente' ? 'selected' : '' }}>Urgente</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-400 uppercase">Asignar a</label>
                        <select wire:change="assignTo($event.target.value)" class="w-full bg-slate-50 border-2 border-slate-100 rounded-lg h-10 px-3">
                            <option value="">Sin asignar</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ $task->assigned_to_id === $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl border-2 border-slate-100 shadow-lg p-6 space-y-4">
                <h3 class="font-bold text-slate-800 text-lg border-b pb-2">Información</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $task->requestedBy->name ?? 'N/A' }}</p>
                            <p class="text-[10px] text-slate-400 uppercase">{{ $task->requestedBy->department ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="flex justify-between text-sm py-2 border-b border-slate-50">
                        <span class="text-slate-400">Creada</span>
                        <span class="font-bold text-slate-700">{{ $task->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($task->due_date)
                        <div class="flex justify-between text-sm py-2 border-b border-slate-50">
                            <span class="text-slate-400">Vencimiento</span>
                            <span class="font-bold text-slate-700">{{ $task->due_date->format('d/m/Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
