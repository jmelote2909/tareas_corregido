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
    $getPriorityBadge = function($p) {
        return match($p) {
            'urgente' => '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-gradient-to-r from-red-600 to-rose-600 text-white shadow-lg shadow-rose-500/30 border border-red-700 animate-pulse uppercase tracking-wider">🔥 ¡URGENTE!</span>',
            'alta' => '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-md shadow-orange-500/20 border border-orange-500 uppercase tracking-wide">⚡ ALTA</span>',
            'media' => '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-500 text-white border border-blue-600">✨ Media</span>',
            'baja' => '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-200 text-slate-700 border border-slate-300">Baja</span>',
            default => '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-200 text-slate-700 border border-slate-300">' . ucfirst($p) . '</span>'
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
    <div class="rounded-xl p-6 mb-6 text-white shadow-lg animate-in fade-in duration-300" style="background: linear-gradient(135deg, {{ $empColor }}, {{ $empColor }}dd)">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $getStatusColor($task->status) }}">{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                    {!! $getPriorityBadge($task->priority) !!}
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
                        <button wire:click="saveEdit" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold transition-all active:scale-[0.98]" style="background:#16a34a;color:#fff;box-shadow:0 4px 14px rgba(22,163,74,.35);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Guardar
                        </button>
                        <button wire:click="cancelEditing" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold transition-all" style="background:rgba(255,255,255,0.2);color:#fff;">
                            Cancelar
                        </button>
                    @else
                        <button wire:click="startEditing" class="p-2.5 rounded-xl hover:bg-white/20 transition-all" title="Editar Tarea">
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
                            <input type="text" wire:model="editTitle" class="w-full bg-slate-50 border-2 border-slate-100 rounded-lg h-10 px-3 mt-1 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            @error('editTitle') <span class="text-xs text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase">Descripción</label>
                            <textarea wire:model="editDescription" rows="5" class="w-full bg-slate-50 border-2 border-slate-100 rounded-lg p-3 mt-1 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20"></textarea>
                            @error('editDescription') <span class="text-xs text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase">Fecha Límite (Vencimiento)</label>
                            <input type="date" wire:model="editDueDate" class="w-full bg-slate-50 border-2 border-slate-100 rounded-lg h-10 px-3 mt-1 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            @error('editDueDate') <span class="text-xs text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="flex gap-3 pt-4 border-t border-slate-100 justify-end">
                            <button type="button" wire:click="cancelEditing" class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all active:scale-[0.98]" style="background:#f1f5f9;color:#475569;">
                                Cancelar
                            </button>
                            <button type="button" wire:click="saveEdit" class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all active:scale-[0.98]" style="background:linear-gradient(135deg,#3b82f6,#4f46e5);color:#fff;box-shadow:0 4px 14px rgba(79,70,229,.25);">
                                💾 Guardar Cambios
                            </button>
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

                        {{-- Documents List --}}
                        @php $documents = $task->attachments->where('type', 'document'); @endphp
                        @if($documents->count() > 0)
                            <div class="space-y-3">
                                <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest">Documentos Adjuntos</h4>
                                @foreach($documents as $doc)
                                    @php
                                        $ext = strtolower(pathinfo($doc->name, PATHINFO_EXTENSION));
                                    @endphp
                                    <div class="bg-emerald-50 rounded-2xl p-4 border-2 border-emerald-100 relative group flex items-center justify-between">
                                        <a href="{{ $doc->url }}" target="_blank" class="flex items-center gap-3 flex-1 min-w-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-500 shrink-0"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-slate-700 truncate" title="{{ $doc->name }}">{{ $doc->name }}</p>
                                                <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest bg-emerald-100/50 px-2 py-0.5 rounded-md mt-1 inline-block">{{ $ext ?: 'doc' }}</span>
                                            </div>
                                        </a>
                                        
                                        {{-- Delete Document Button --}}
                                        <button type="button" wire:click="deleteAttachment('{{ $doc->id }}')" wire:confirm="¿Estás seguro de que deseas eliminar este documento?" class="text-red-500 hover:text-red-700 transition-colors font-bold text-xs flex items-center gap-1 opacity-0 group-hover:opacity-100 duration-200 ml-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                            Eliminar
                                        </button>
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
                        Añadir más fotos, audios o documentos
                    </h4>
                    
                    @if (session()->has('success_attachments'))
                        <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-bold mb-4">
                            {{ session('success_attachments') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="addAttachments" class="space-y-4">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            {{-- Photos --}}
                            <label class="cursor-pointer">
                                <div class="flex items-center justify-center gap-2 border-2 border-purple-100 bg-purple-50 hover:bg-purple-100 transition-colors rounded-xl h-12 text-purple-700 font-semibold text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                                    Añadir Fotos
                                </div>
                                <input type="file" wire:model="newPhotos" multiple class="hidden" accept="image/*">
                            </label>
                            
                            {{-- Audio File (Upload) --}}
                            <label class="cursor-pointer">
                                <div class="flex items-center justify-center gap-2 border-2 border-blue-100 bg-blue-50 hover:bg-blue-100 transition-colors rounded-xl h-12 text-blue-700 font-semibold text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                                    Subir Audio
                                </div>
                                <input type="file" wire:model="newAudio" class="hidden" accept=".mp3,.wav,.m4a,.aac,.ogg,.wma,.amr,.flac,.opus,.caf,.weba,.webm">
                            </label>

                            {{-- Live Audio Recorder (Record) --}}
                            <button type="button" @click="$dispatch('toggle-voice-recorder-new')" class="flex items-center justify-center gap-2 border-2 border-rose-100 bg-rose-50 hover:bg-rose-100 transition-colors rounded-xl h-12 text-rose-700 font-semibold text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v1a7 7 0 0 1-14 0v-1"/><line x1="12" x2="12" y1="19" y2="22"/></svg>
                                Grabar Nota
                            </button>

                            {{-- Document File --}}
                            <label class="cursor-pointer">
                                <div class="flex items-center justify-center gap-2 border-2 border-emerald-100 bg-emerald-50 hover:bg-emerald-100 transition-colors rounded-xl h-12 text-emerald-700 font-semibold text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                                    Subir Documento
                                </div>
                                <input type="file" wire:model="newDocuments" multiple class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt">
                            </label>
                        </div>

                        {{-- Live Audio Recorder Panel --}}
                        <div x-data="voiceRecorderComponent('newAudio')" 
                             @toggle-voice-recorder-new.window="toggle()" 
                             class="mt-3 animate-in slide-in-from-top-2 duration-300"
                             x-show="show" 
                             x-cloak
                             style="display: none;">
                            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 shadow-xl text-white">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="relative flex h-3 w-3" x-show="isRecording">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                                        </div>
                                        <div class="h-3 w-3 rounded-full bg-slate-500" x-show="!isRecording"></div>
                                        <span class="text-sm font-black tracking-widest font-mono text-slate-100" x-text="formattedTime">00:00</span>
                                    </div>

                                    <div class="flex items-center gap-0.5 h-6 shrink-0" x-show="isRecording">
                                        <div class="w-0.5 bg-rose-500 rounded-full animate-[wave_1.2s_ease-in-out_infinite]" style="height: 40%;"></div>
                                        <div class="w-0.5 bg-rose-500 rounded-full animate-[wave_0.8s_ease-in-out_infinite]" style="height: 80%;"></div>
                                        <div class="w-0.5 bg-rose-500 rounded-full animate-[wave_1.5s_ease-in-out_infinite]" style="height: 50%;"></div>
                                        <div class="w-0.5 bg-rose-500 rounded-full animate-[wave_1s_ease-in-out_infinite]" style="height: 90%;"></div>
                                        <div class="w-0.5 bg-rose-500 rounded-full animate-[wave_0.7s_ease-in-out_infinite]" style="height: 60%;"></div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button type="button" 
                                                x-show="!isRecording && !isUploading" 
                                                @click="startRecording()" 
                                                class="px-4 py-2 bg-rose-600 hover:bg-rose-700 active:scale-95 transition-all text-white font-extrabold text-xs rounded-xl shadow-md shadow-rose-600/25 flex items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v1a7 7 0 0 1-14 0v-1"/><line x1="12" x2="12" y1="19" y2="22"/></svg>
                                            Iniciar Micrófono
                                        </button>

                                        <button type="button" 
                                                x-show="isRecording" 
                                                @click="stopRecording()" 
                                                class="px-4 py-2 bg-rose-600 hover:bg-rose-700 active:scale-95 transition-all text-white font-extrabold text-xs rounded-xl shadow-md shadow-rose-600/25 flex items-center gap-1.5 animate-pulse">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/></svg>
                                            Detener y Guardar
                                        </button>

                                        <button type="button" 
                                                @click="toggle()" 
                                                x-show="!isRecording && !isUploading" 
                                                class="px-3 py-2 bg-slate-800 hover:bg-slate-750 active:scale-95 transition-all text-slate-300 font-bold text-xs rounded-xl">
                                            Cerrar
                                        </button>
                                    </div>
                                </div>

                                <div x-show="isUploading" class="mt-3 text-xs font-bold text-blue-400 flex items-center gap-2 animate-pulse">
                                    <svg class="animate-spin h-4 w-4 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Subiendo grabación al servidor...
                                </div>

                                <div x-show="errorMessage" x-text="errorMessage" class="mt-3 text-xs font-bold text-rose-500 bg-rose-500/10 border border-rose-500/20 px-3 py-2 rounded-xl"></div>
                            </div>
                        </div>

                        {{-- Previews --}}
                        @if($newAudio || count($newPhotos) > 0 || count($newDocuments) > 0)
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

                                @if(count($newDocuments) > 0)
                                    <div class="space-y-2">
                                        @foreach($newDocuments as $index => $doc)
                                            <div class="flex items-center gap-2 text-xs font-bold text-emerald-700 bg-emerald-50 p-2.5 rounded-lg border border-emerald-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                                                <span class="truncate flex-1">{{ $doc->getClientOriginalName() }}</span>
                                                <button type="button" wire:click="newDocuments.splice({{ $index }}, 1)" class="text-emerald-400 hover:text-emerald-600 font-black px-1.5 py-0.5 rounded-md hover:bg-emerald-100">✕</button>
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
                    
                    @if($isEditing)
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-400 uppercase">Estado</label>
                            <select wire:model="editStatus" class="w-full bg-slate-50 border-2 border-slate-100 rounded-lg h-10 px-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                <option value="pendiente">Pendiente</option>
                                <option value="en_proceso">En Proceso</option>
                                <option value="completada">Completada</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-400 uppercase">Prioridad</label>
                            <select wire:model="editPriority" class="w-full bg-slate-50 border-2 border-slate-100 rounded-lg h-10 px-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                <option value="baja">Baja</option>
                                <option value="media">Media</option>
                                <option value="alta">Alta</option>
                                <option value="urgente">Urgente</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-400 uppercase">Asignar a</label>
                            <select wire:model="editAssignedToId" class="w-full bg-slate-50 border-2 border-slate-100 rounded-lg h-10 px-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                <option value="">Sin asignar</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pt-3 flex gap-2">
                            <button type="button" wire:click="saveEdit" class="flex-1 py-2.5 font-bold text-xs rounded-xl active:scale-[0.98] transition-all text-center" style="background:#16a34a;color:#fff;box-shadow:0 4px 12px rgba(22,163,74,.3);">
                                💾 Guardar
                            </button>
                            <button type="button" wire:click="cancelEditing" class="flex-1 py-2.5 font-bold text-xs rounded-xl active:scale-[0.98] transition-all text-center" style="background:#f1f5f9;color:#64748b;">
                                Cancelar
                            </button>
                        </div>
                    @else
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-400 uppercase">Estado</label>
                            <div class="flex mt-1">
                                <span class="px-3 py-1.5 rounded-xl text-xs font-bold {{ $getStatusColor($task->status) }}">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-400 uppercase">Prioridad</label>
                            <div class="flex mt-1">
                                {!! $getPriorityBadge($task->priority) !!}
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-400 uppercase">Asignado a</label>
                            @if($task->assignedTo)
                                <div class="flex items-center gap-2.5 p-3 bg-slate-50 border border-slate-100 rounded-xl mt-1">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs shrink-0" style="background-color: {{ $task->assignedTo->color ?? '#6366f1' }}">
                                        {{ substr($task->assignedTo->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-bold text-slate-700">{{ $task->assignedTo->name }}</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2 p-3 bg-slate-50 border border-dashed border-slate-200 rounded-xl text-slate-400 mt-1 italic text-xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    Sin asignar
                                </div>
                            @endif
                        </div>

                        <div class="pt-2">
                            <button type="button" wire:click="startEditing" class="w-full flex items-center justify-center gap-2 bg-[#3b49ff] hover:bg-[#3240e6] transition-all text-white font-bold py-2.5 rounded-xl shadow-md shadow-blue-500/10 active:scale-[0.98]">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                Editar o Asignar
                            </button>
                        </div>
                    @endif

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

<style>
    @keyframes wave {
        0%, 100% { transform: scaleY(0.3); }
        50% { transform: scaleY(1); }
    }
</style>

<script>
    if (typeof window.voiceRecorderComponent === 'undefined') {
        window.voiceRecorderComponent = function(wireVarName = 'audio') {
            return {
                show: false,
                isRecording: false,
                mediaRecorder: null,
                audioChunks: [],
                timerInterval: null,
                secondsElapsed: 0,
                isUploading: false,
                errorMessage: '',

                toggle() {
                    this.show = !this.show;
                    if (!this.show) {
                        this.stopRecording();
                        this.errorMessage = '';
                    }
                },

                async startRecording() {
                    this.errorMessage = '';
                    this.audioChunks = [];
                    this.secondsElapsed = 0;

                    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                        this.errorMessage = 'Tu navegador no soporta la grabación de audio o necesitas usar una conexión segura (HTTPS).';
                        return;
                    }
                    
                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        
                        let options = { mimeType: 'audio/webm' };
                        if (!MediaRecorder.isTypeSupported('audio/webm')) {
                            options = { mimeType: 'audio/ogg' };
                        }
                        if (!MediaRecorder.isTypeSupported('audio/ogg')) {
                            options = { mimeType: 'audio/mp4' };
                        }

                        this.mediaRecorder = new MediaRecorder(stream, options);
                        
                        this.mediaRecorder.ondataavailable = (event) => {
                            if (event.data.size > 0) {
                                this.audioChunks.push(event.data);
                            }
                        };

                        this.mediaRecorder.onstop = async () => {
                            const mime = this.mediaRecorder.mimeType || 'audio/webm';
                            const ext = mime.includes('ogg') ? 'ogg' : (mime.includes('mp4') ? 'm4a' : 'webm');
                            const audioBlob = new Blob(this.audioChunks, { type: mime });
                            const file = new File([audioBlob], `grabacion_${Date.now()}.${ext}`, { type: mime });
                            
                            this.isUploading = true;
                            
                            @this.upload(wireVarName, file, 
                                (uploadedFilename) => {
                                    this.isUploading = false;
                                    this.show = false;
                                }, 
                                (error) => {
                                    this.isUploading = false;
                                    this.errorMessage = 'Error al subir la grabación. Inténtalo de nuevo.';
                                }
                            );

                            stream.getTracks().forEach(track => track.stop());
                        };

                        this.mediaRecorder.start(250);
                        this.isRecording = true;
                        
                        this.timerInterval = setInterval(() => {
                            this.secondsElapsed++;
                        }, 1000);

                    } catch (err) {
                        console.error(err);
                        this.errorMessage = 'No se pudo acceder al micrófono. Concede el permiso en la barra de direcciones.';
                    }
                },

                stopRecording() {
                    if (this.mediaRecorder && this.mediaRecorder.state !== 'inactive') {
                        this.mediaRecorder.stop();
                        clearInterval(this.timerInterval);
                        this.isRecording = false;
                    }
                },

                get formattedTime() {
                    const mins = String(Math.floor(this.secondsElapsed / 60)).padStart(2, '0');
                    const secs = String(this.secondsElapsed % 60).padStart(2, '0');
                    return `${mins}:${secs}`;
                }
            };
        };
    }
</script>
