@php
    $getStatusColor = function($s) {
        return match($s) {
            'Completado' => 'bg-emerald-500 text-white',
            'En Progreso' => 'bg-blue-500 text-white',
            'Planificación' => 'bg-amber-500 text-white',
            'Detenido' => 'bg-red-500 text-white',
            default => 'bg-gray-100 text-gray-800'
        };
    };
    $getPriorityBadge = function($p) {
        return match($p) {
            'Crítica' => '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black bg-gradient-to-r from-red-600 to-rose-600 text-white shadow-lg shadow-rose-500/30 border border-red-700 animate-pulse uppercase tracking-wider">🔥 ¡CRÍTICA!</span>',
            'Alta' => '<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-extrabold bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-md shadow-orange-500/20 border border-orange-500 uppercase tracking-wide">⚡ ALTA</span>',
            'Media' => '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-500 text-white border border-blue-600">✨ Media</span>',
            'Baja' => '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-200 text-slate-700 border border-slate-300">Baja</span>',
            default => '<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-200 text-slate-700 border border-slate-300">' . ucfirst($p) . '</span>'
        };
    };
    $projectColor = '#8b5cf6'; // Purple for projects
@endphp

<div class="container mx-auto px-4 py-8">
    <a href="{{ route('proyectos') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
        Volver a Proyectos
    </a>

    {{-- Project Header Bar --}}
    <div class="rounded-xl p-6 mb-6 text-white shadow-lg animate-in fade-in duration-300" style="background: linear-gradient(135deg, {{ $projectColor }}, {{ $projectColor }}dd)">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $getStatusColor($project->status) }}">{{ $project->status }}</span>
                    {!! $getPriorityBadge($project->priority) !!}
                </div>
                <h1 class="text-2xl font-black">{{ $project->name }}</h1>
                <div class="flex items-center gap-2 mt-2">
                    @foreach($project->users as $user)
                        <div class="w-6 h-6 rounded-full border border-white/30 bg-white/20 flex items-center justify-center text-[10px] font-bold text-white shadow-sm" title="{{ $user->name }}">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endforeach
                    @if($project->users->isEmpty())
                        <span class="text-[10px] text-white/70 italic font-medium bg-white/10 px-2 py-0.5 rounded-full">Sin asignar</span>
                    @endif
                </div>
            </div>
            <div class="flex gap-2">
                @if($isAdmin)
                    @if($isEditing)
                        <button wire:click="saveEdit" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold transition-all active:scale-[0.98]" style="background:#16a34a;color:#fff;box-shadow:0 4px 14px rgba(22,163,74,.35);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Guardar
                        </button>
                        <button wire:click="cancelEditing" class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold transition-all" style="background:rgba(255,255,255,0.2);color:#fff;">
                            Cancelar
                        </button>
                    @else
                        <button wire:click="startEditing" class="p-2.5 rounded-xl hover:bg-white/20 transition-all" title="Editar Proyecto">
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
                            <label class="text-xs font-bold text-slate-500 uppercase">Nombre del Proyecto</label>
                            <input type="text" wire:model="editName" class="w-full bg-slate-50 border-2 border-slate-100 rounded-lg h-10 px-3 mt-1 text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500/20">
                            @error('editName') <span class="text-xs text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="text-xs font-bold text-slate-500 uppercase">Descripción</label>
                            <textarea wire:model="editDescription" rows="5" class="w-full bg-slate-50 border-2 border-slate-100 rounded-lg p-3 mt-1 text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500/20"></textarea>
                            @error('editDescription') <span class="text-xs text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="flex gap-3 pt-4 border-t border-slate-100 justify-end">
                            <button type="button" wire:click="cancelEditing" class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all active:scale-[0.98]" style="background:#f1f5f9;color:#475569;">
                                Cancelar
                            </button>
                            <button type="button" wire:click="saveEdit" class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all active:scale-[0.98]" style="background:linear-gradient(135deg,#8b5cf6,#6366f1);color:#fff;box-shadow:0 4px 14px rgba(139,92,246,.25);">
                                💾 Guardar Cambios
                            </button>
                        </div>
                    </div>
                @else
                    <p class="text-slate-600 whitespace-pre-wrap">{{ $project->description }}</p>
                @endif
            </div>

            {{-- History & Comments --}}
            <div class="bg-white rounded-2xl border-2 border-slate-100 shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 p-4 border-b-2 border-slate-100 flex items-center gap-2">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-purple-600"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M12 7v5l4 2"/></svg>
                    </div>
                    <h3 class="font-bold text-slate-800">Historial y Comentarios</h3>
                    <span class="bg-purple-100 text-purple-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ $histories->count() }}</span>
                </div>
                <div class="p-6 space-y-4">
                    @forelse($histories as $history)
                        @if($history->type === 'system')
                            <div class="flex items-center justify-center my-3">
                                <div class="bg-indigo-50 text-indigo-700 text-[10px] font-bold px-4 py-2 rounded-full border border-indigo-100/50 shadow-sm">
                                    {{ $history->text }} - <span class="opacity-70">{{ $history->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        @else
                            <div class="flex gap-4">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-400 to-indigo-500 flex items-center justify-center text-white font-bold flex-shrink-0 shadow-sm">
                                    {{ substr($history->user_name, 0, 1) }}
                                </div>
                                <div class="flex-1 bg-slate-50 rounded-2xl p-4 border border-slate-100/50">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="font-bold text-sm text-slate-800">{{ $history->user_name }}</span>
                                        <span class="text-[10px] text-slate-400 font-medium">{{ $history->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-slate-600">{{ $history->text }}</p>
                                </div>
                            </div>
                        @endif
                    @empty
                        <p class="text-center text-slate-400 italic py-4">Aún no hay actividad ni comentarios en este proyecto</p>
                    @endforelse

                    <div class="pt-4 border-t border-slate-100 flex gap-3 mt-4">
                        <textarea wire:model="newComment" placeholder="Escribe un comentario..." rows="2" class="flex-1 bg-slate-50 border-2 border-slate-100 rounded-xl p-3 text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"></textarea>
                        <button wire:click="addComment" class="px-6 bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-bold rounded-xl shadow-md hover:scale-[1.02] active:scale-[0.98] transition-all">
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
                
                @if($attachments->count() > 0)
                    <div class="space-y-6 mb-6">
                        @php 
                            $images = collect();
                            $audios = collect();
                            $documents = collect();
                            
                            foreach($attachments as $att) {
                                $ext = strtolower(pathinfo($att->image_path, PATHINFO_EXTENSION));
                                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                                    $images->push($att);
                                } elseif (in_array($ext, ['mp3', 'wav', 'm4a', 'ogg', 'webm'])) {
                                    $audios->push($att);
                                } else {
                                    $documents->push($att);
                                }
                            }
                        @endphp
                        
                        {{-- Images Grid --}}
                        @if($images->count() > 0)
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                @foreach($images as $img)
                                    <div class="relative group aspect-square rounded-xl overflow-hidden border-2 border-slate-50 hover:border-indigo-200 transition-all shadow-sm">
                                        <a href="{{ asset('storage/' . $img->image_path) }}" target="_blank" class="block w-full h-full">
                                            <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 flex items-center justify-center transition-all opacity-0 group-hover:opacity-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>
                                            </div>
                                        </a>
                                        @if($isAdmin)
                                            <button type="button" wire:click="deleteAttachment('{{ $img->id }}')" wire:confirm="¿Estás seguro de que deseas eliminar esta imagen?" class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white rounded-full w-8 h-8 flex items-center justify-center shadow-lg transition-all transform scale-0 group-hover:scale-100 duration-200 z-10">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Audio Player --}}
                        @if($audios->count() > 0)
                            <div class="space-y-3">
                                @foreach($audios as $audio)
                                    <div class="bg-indigo-50 rounded-2xl p-4 border-2 border-indigo-100 relative group">
                                        <div class="flex items-center justify-between mb-3">
                                            <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v1a7 7 0 0 1-14 0v-1"/><line x1="12" x2="12" y1="19" y2="22"/></svg>
                                                Nota de voz adjunta
                                            </p>
                                            @if($isAdmin)
                                                <button type="button" wire:click="deleteAttachment('{{ $audio->id }}')" wire:confirm="¿Estás seguro de eliminar este audio?" class="text-red-500 hover:text-red-700 transition-colors font-bold text-xs flex items-center gap-1 opacity-0 group-hover:opacity-100 duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                                    Eliminar
                                                </button>
                                            @endif
                                        </div>
                                        <audio controls class="w-full h-10">
                                            <source src="{{ asset('storage/' . $audio->image_path) }}">
                                            Tu navegador no soporta el elemento de audio.
                                        </audio>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Documents List --}}
                        @if($documents->count() > 0)
                            <div class="space-y-3">
                                <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest">Documentos Adjuntos</h4>
                                @foreach($documents as $doc)
                                    @php
                                        $ext = strtolower(pathinfo($doc->image_path, PATHINFO_EXTENSION));
                                    @endphp
                                    <div class="bg-emerald-50 rounded-2xl p-4 border-2 border-emerald-100 relative group flex items-center justify-between">
                                        <a href="{{ asset('storage/' . $doc->image_path) }}" target="_blank" class="flex items-center gap-3 flex-1 min-w-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-500 shrink-0"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-slate-700 truncate" title="{{ basename($doc->image_path) }}">{{ basename($doc->image_path) }}</p>
                                                <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest bg-emerald-100/50 px-2 py-0.5 rounded-md mt-1 inline-block">{{ $ext ?: 'doc' }}</span>
                                            </div>
                                        </a>
                                        @if($isAdmin)
                                            <button type="button" wire:click="deleteAttachment('{{ $doc->id }}')" wire:confirm="¿Estás seguro de eliminar este documento?" class="text-red-500 hover:text-red-700 transition-colors font-bold text-xs flex items-center gap-1 opacity-0 group-hover:opacity-100 duration-200 ml-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                                Eliminar
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-center text-slate-400 italic py-4 mb-4">No hay archivos adjuntos en este proyecto todavía</p>
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
                            <label class="cursor-pointer">
                                <div class="flex items-center justify-center gap-2 border-2 border-purple-100 bg-purple-50 hover:bg-purple-100 transition-colors rounded-xl h-12 text-purple-700 font-semibold text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                                    Añadir Fotos
                                </div>
                                <input type="file" wire:model="newPhotos" multiple class="hidden" accept="image/*">
                            </label>
                            
                            <label class="cursor-pointer">
                                <div class="flex items-center justify-center gap-2 border-2 border-blue-100 bg-blue-50 hover:bg-blue-100 transition-colors rounded-xl h-12 text-blue-700 font-semibold text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                                    Subir Audio
                                </div>
                                <input type="file" wire:model="newAudio" class="hidden" accept=".mp3,.wav,.m4a,.aac,.ogg,.wma,.amr,.flac,.opus,.caf,.weba,.webm">
                            </label>

                            <button type="button" @click="$dispatch('toggle-voice-recorder-new')" class="flex items-center justify-center gap-2 border-2 border-rose-100 bg-rose-50 hover:bg-rose-100 transition-colors rounded-xl h-12 text-rose-700 font-semibold text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v1a7 7 0 0 1-14 0v-1"/><line x1="12" x2="12" y1="19" y2="22"/></svg>
                                Grabar Nota
                            </button>

                            <label class="cursor-pointer">
                                <div class="flex items-center justify-center gap-2 border-2 border-emerald-100 bg-emerald-50 hover:bg-emerald-100 transition-colors rounded-xl h-12 text-emerald-700 font-semibold text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                                    Subir Documento
                                </div>
                                <input type="file" wire:model="newDocuments" multiple class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt">
                            </label>
                        </div>

                        {{-- Previews --}}
                        @if($newAudio || count($newPhotos) > 0 || count($newDocuments) > 0)
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-3 mt-3 animate-in fade-in duration-200">
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Archivos listos para subir:</div>
                                
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

                                <button type="submit" wire:loading.attr="disabled" class="w-full h-11 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-xl text-sm shadow-md hover:scale-[1.01] active:scale-[0.99] transition-all flex items-center justify-center gap-2 mt-2">
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
                            <select wire:model="editStatus" class="w-full bg-slate-50 border-2 border-slate-100 rounded-lg h-10 px-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500/20">
                                <option value="Planificación">Planificación</option>
                                <option value="En Progreso">En Progreso</option>
                                <option value="Detenido">Detenido</option>
                                <option value="Completado">Completado</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-400 uppercase">Prioridad</label>
                            <select wire:model="editPriority" class="w-full bg-slate-50 border-2 border-slate-100 rounded-lg h-10 px-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500/20">
                                <option value="Baja">Baja</option>
                                <option value="Media">Media</option>
                                <option value="Alta">Alta</option>
                                <option value="Crítica">Crítica</option>
                            </select>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-400 uppercase">Fecha Inicio</label>
                            <input type="date" wire:model="editStartDate" class="w-full bg-slate-50 border-2 border-slate-100 rounded-lg h-10 px-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500/20">
                        </div>
                        
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-400 uppercase">Fecha Fin</label>
                            <input type="date" wire:model="editEndDate" class="w-full bg-slate-50 border-2 border-slate-100 rounded-lg h-10 px-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500/20">
                        </div>
                        
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-400 uppercase">Presupuesto (€)</label>
                            <input type="number" wire:model="editBudget" class="w-full bg-slate-50 border-2 border-slate-100 rounded-lg h-10 px-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-purple-500/20">
                        </div>

                        <div class="space-y-1 pt-2">
                            <label class="text-xs font-bold text-slate-400 uppercase block mb-1">Equipo Asignado</label>
                            <div class="max-h-40 overflow-y-auto bg-slate-50 border-2 border-slate-100 rounded-lg p-2 space-y-1 custom-scrollbar">
                                @foreach($users as $u)
                                    <label class="flex items-center gap-2 p-1.5 hover:bg-slate-100 rounded cursor-pointer">
                                        <input type="checkbox" wire:model.live="editSelectedUsers" value="{{ $u->id }}" class="rounded text-purple-600 focus:ring-purple-500 border-slate-300">
                                        <span class="text-xs font-medium text-slate-700">{{ $u->name }}</span>
                                    </label>
                                @endforeach
                            </div>
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
                                <span class="px-3 py-1.5 rounded-xl text-xs font-bold {{ $getStatusColor($project->status) }}">
                                    {{ $project->status }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-400 uppercase">Prioridad</label>
                            <div class="flex mt-1">
                                {!! $getPriorityBadge($project->priority) !!}
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="button" wire:click="startEditing" class="w-full flex items-center justify-center gap-2 bg-[#8b5cf6] hover:bg-[#7c3aed] transition-all text-white font-bold py-2.5 rounded-xl shadow-md shadow-purple-500/10 active:scale-[0.98]">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                Editar Parámetros
                            </button>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-slate-100">
                        <button wire:click="deleteProject" wire:confirm="¿Estás seguro de que deseas eliminar este proyecto permanentemente?" class="w-full flex items-center justify-center gap-2 bg-red-50 hover:bg-red-500 hover:text-white transition-all text-red-600 font-bold py-2.5 rounded-xl border border-red-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            Eliminar Proyecto
                        </button>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl border-2 border-slate-100 shadow-lg p-6 space-y-4">
                <h3 class="font-bold text-slate-800 text-lg border-b pb-2">Información del Proyecto</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm py-2 border-b border-slate-50">
                        <span class="text-slate-400">Fecha Inicio</span>
                        <span class="font-bold text-slate-700">{{ $project->start_date ? $project->start_date->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between text-sm py-2 border-b border-slate-50">
                        <span class="text-slate-400">Fecha Fin (Prevista)</span>
                        <span class="font-bold text-slate-700">{{ $project->end_date ? $project->end_date->format('d/m/Y') : 'No definida' }}</span>
                    </div>
                    <div class="flex justify-between text-sm py-2 border-b border-slate-50">
                        <span class="text-slate-400">Presupuesto</span>
                        <span class="font-bold text-slate-700">{{ $project->budget ? number_format($project->budget, 0) . ' €' : 'No definido' }}</span>
                    </div>
                    <div class="py-2">
                        <span class="text-slate-400 block mb-2 text-sm">Equipo Asignado</span>
                        <div class="flex flex-wrap gap-2">
                            @foreach($project->users as $user)
                                <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-100 rounded-full px-2 py-1">
                                    <div class="w-5 h-5 rounded-full bg-indigo-500 text-white flex items-center justify-center text-[9px] font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-600">{{ $user->name }}</span>
                                </div>
                            @endforeach
                            @if($project->users->isEmpty())
                                <span class="text-xs text-slate-400 italic">Sin equipo asignado</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
