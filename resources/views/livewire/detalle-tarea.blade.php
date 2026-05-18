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
    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors mb-4">
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
                <p class="text-white/70 text-sm mt-1">Solicitud de {{ $task->requester_name ?? $task->requestedBy->name ?? 'N/A' }} - {{ $task->requester_department ?? $task->requestedBy->department ?? 'N/A' }}</p>
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
            <div class="bg-white rounded-2xl border-2 border-slate-100 shadow-lg p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-500"><path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 18 8.84l-8.59 8.51a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                    Archivos Adjuntos
                </h3>
                
                @if($task->attachments->count() > 0)
                    <div class="space-y-6 mb-6">
                        {{-- Images Grid --}}
                        @php $images = $task->attachments->where('type', 'image'); @endphp
                        @if($images->count() > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                @foreach($images as $img)
                                    <div class="relative group aspect-square rounded-xl overflow-hidden border-2 border-slate-50 hover:border-indigo-200 transition-all shadow-sm">
                                        <a href="{{ $img->url }}" target="_blank" class="block w-full h-full">
                                            <img src="{{ $img->url }}" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 flex items-center justify-center transition-all opacity-0 group-hover:opacity-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                                            </div>
                                        </a>
                                        {{-- Delete Photo Button --}}
                                        <button type="button" wire:click="deleteAttachment('{{ $img->id }}')" wire:confirm="¿Estás seguro de que deseas eliminar esta imagen?" class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg transition-all transform scale-0 group-hover:scale-100 duration-200 z-10">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Audio Player --}}
                        @php $audios = $task->attachments->where('type', 'audio'); @endphp
                        @if($audios->count() > 0)
                            <div class="space-y-3">
                                @foreach($audios as $audio)
                                    <div class="bg-indigo-50 rounded-2xl p-4 border-2 border-indigo-100 relative group">
                                        <div class="flex items-center justify-between mb-3">
                                            <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v1a7 7 0 0 1-14 0v-1"/><line x1="12" x2="12" y1="19" y2="22"/></svg>
                                                Nota de voz adjunta
                                            </p>
                                            
                                            {{-- Delete Audio Button --}}
                                            <button type="button" wire:click="deleteAttachment('{{ $audio->id }}')" wire:confirm="¿Estás seguro de que deseas eliminar este archivo de audio?" class="text-red-500 hover:text-red-700 transition-colors font-bold text-xs flex items-center gap-1 opacity-0 group-hover:opacity-100 duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                                Eliminar
                                            </button>
                                        </div>
                                        <audio controls class="w-full h-10">
                                            <source src="{{ $audio->url }}">
                                            Tu navegador no soporta el elemento de audio.
                                        </audio>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-center text-slate-400 italic py-4 mb-4">No hay archivos adjuntos en esta tarea todavía</p>
                @endif

                {{-- Add new attachments form --}}
                <div class="pt-6 border-t border-slate-100">
                    <h4 class="text-sm font-bold text-slate-700 mb-3 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        Añadir más fotos o audios
                    </h4>
                    
                    @if (session()->has('success_attachments'))
                        <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-bold mb-4">
                            {{ session('success_attachments') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="addAttachments" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            {{-- Photos --}}
                            <label class="cursor-pointer">
                                <div class="flex items-center justify-center gap-2 border-2 border-purple-100 bg-purple-50 hover:bg-purple-100 transition-colors rounded-xl h-12 text-purple-700 font-semibold text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                                    Añadir Fotos
                                </div>
                                <input type="file" wire:model="newPhotos" multiple class="hidden" accept="image/*">
                            </label>
                            
                            {{-- Audio File --}}
                            <label class="cursor-pointer">
                                <div class="flex items-center justify-center gap-2 border-2 border-blue-100 bg-blue-50 hover:bg-blue-100 transition-colors rounded-xl h-12 text-blue-700 font-semibold text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                                    Subir Audio
                                </div>
                                <input type="file" wire:model="newAudio" class="hidden" accept="audio/*, .aac, .m4a, .mp3, .wav, audio/aac, audio/x-aac, audio/mp4, audio/m4a">
                            </label>
                        </div>

                        {{-- Previews --}}
                        @if($newAudio || count($newPhotos) > 0)
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-3 mt-3 animate-in fade-in duration-200">
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Archivos seleccionados listos para subir:</div>
                                
                                @if($newAudio)
                                    <div class="flex items-center gap-2 text-xs font-bold text-blue-700 bg-blue-50 p-2.5 rounded-lg border border-blue-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
                                        <span class="truncate flex-1">{{ $newAudio->getClientOriginalName() }}</span>
                                        <button type="button" wire:click="$set('newAudio', null)" class="text-blue-400 hover:text-blue-600 font-black px-1.5 py-0.5 rounded-md hover:bg-blue-100">✕</button>
                                    </div>
                                @endif

                                @if(count($newPhotos) > 0)
                                    <div class="grid grid-cols-4 gap-2">
                                        @foreach($newPhotos as $index => $photo)
                                            <div class="relative group aspect-square rounded-lg overflow-hidden border-2 border-slate-200 shadow-sm bg-white">
                                                <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                                                <button type="button" wire:click="newPhotos.splice({{ $index }}, 1)" class="absolute top-1 right-1 bg-black/50 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px] font-bold hover:bg-red-500 transition-colors">✕</button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <button type="submit" wire:loading.attr="disabled" class="w-full h-11 bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-bold rounded-xl text-sm shadow-md hover:scale-[1.01] active:scale-[0.99] transition-all flex items-center justify-center gap-2 mt-2">
                                    <span wire:loading.remove>Guardar Nuevos Adjuntos</span>
                                    <span wire:loading>Guardando...</span>
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
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

                    <div class="pt-4 border-t border-slate-100">
                        <button wire:click="deleteTask" wire:confirm="¿Estás seguro de que deseas eliminar esta tarea permanentemente?" class="w-full flex items-center justify-center gap-2 bg-red-50 hover:bg-red-500 hover:text-white transition-all text-red-600 font-bold py-2.5 rounded-xl border border-red-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            Eliminar Tarea
                        </button>
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
                            <p class="text-sm font-bold text-slate-800">{{ $task->requester_name ?? $task->requestedBy->name ?? 'N/A' }}</p>
                            <p class="text-[10px] text-slate-400 uppercase">{{ $task->requester_department ?? $task->requestedBy->department ?? 'N/A' }}</p>
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
