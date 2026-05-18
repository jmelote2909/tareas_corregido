<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-blue-50 py-8">
    <div class="container mx-auto px-4 max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                Volver
            </a>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mt-2">
                Nueva Solicitud de Trabajo
            </h1>
            <p class="text-slate-500">Departamento de Infraestructura y TI</p>
        </div>

        <div class="bg-white rounded-2xl border-2 border-slate-100 shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 border-b-2 border-slate-100">
                <h2 class="text-xl font-bold text-slate-800">Formulario de Solicitud</h2>
                <p class="text-sm text-slate-500">Complete todos los campos para enviar su solicitud</p>
            </div>

            <div class="p-6">
                @if($success)
                    <div class="text-center py-8">
                        <div class="h-16 w-16 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-emerald-700 mb-2">Solicitud Enviada</h3>
                        <p class="text-slate-600">Su solicitud ha sido registrada exitosamente.</p>
                    </div>
                @else
                    <form wire:submit.prevent="save" class="space-y-6">
                        @if($error)
                            <div class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                                {{ $error }}
                            </div>
                        @endif

                        {{-- Requester Info --}}
                        <div class="p-4 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-xl border-2 border-blue-100">
                            <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-blue-700">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                Información del Solicitante
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="text-sm font-semibold text-slate-700">Nombre del Solicitante *</label>
                                    <input type="text" wire:model="requesterName" class="w-full bg-white border-2 border-blue-100 rounded-lg h-10 px-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    @error('requesterName') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1">
                                    <label class="text-sm font-semibold text-slate-700">Departamento</label>
                                    <input type="text" wire:model="requesterDepartment" class="w-full bg-white border-2 border-blue-100 rounded-lg h-10 px-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    @error('requesterDepartment') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Title --}}
                        <div class="space-y-1">
                            <label class="text-sm font-semibold text-slate-700">Título de la Solicitud *</label>
                            <input type="text" wire:model="title" placeholder="Ej: Reparación de impresora en oficina 3" class="w-full bg-white border-2 border-slate-200 rounded-lg h-11 px-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                            @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        {{-- Description --}}
                        <div class="space-y-1">
                            <label class="text-sm font-semibold text-slate-700">Descripción Detallada *</label>
                            <textarea wire:model="description" rows="4" placeholder="Describa el problema o solicitud..." class="w-full bg-white border-2 border-slate-200 rounded-lg p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"></textarea>
                            @error('description') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Category --}}
                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Categoría *</label>
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <select wire:model="category_id" class="w-full bg-white border-2 border-slate-200 rounded-lg h-11 px-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                            <option value="">Seleccione...</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" wire:key="cat-{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" wire:click="toggleNewCategoryInput" class="h-11 w-11 flex items-center justify-center bg-indigo-50 text-indigo-600 rounded-lg border-2 border-indigo-100 hover:bg-indigo-100 transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                    </button>
                                </div>
                                @error('category_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                
                                @if($showNewCategoryInput)
                                    <div class="mt-2 p-4 bg-indigo-50 rounded-xl border-2 border-indigo-100 animate-in fade-in slide-in-from-top-2">
                                        <label class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2 block">Gestionar Categorías</label>
                                        
                                        <div class="space-y-2 mb-4 max-h-32 overflow-y-auto">
                                            @foreach($categories as $cat)
                                                <div class="flex items-center justify-between bg-white p-2 rounded-lg border border-indigo-100">
                                                    <span class="text-sm font-bold text-slate-700">{{ $cat->name }}</span>
                                                    <button type="button" wire:click="deleteCategory('{{ $cat->id }}')" class="text-red-400 hover:text-red-600 transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>

                                        <label class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2 block">Nueva Categoría</label>
                                        <div class="flex gap-2">
                                            <input type="text" wire:model="newCategoryName" placeholder="Nombre..." class="flex-1 h-10 bg-white border-2 border-indigo-100 rounded-lg px-3 outline-none focus:border-indigo-500 text-sm font-bold">
                                            <button type="button" wire:click="createCategory" class="px-4 bg-indigo-600 text-white rounded-lg font-bold text-sm">Crear</button>
                                        </div>
                                        @error('newCategoryName') <span class="text-[10px] text-red-500 font-bold mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                            </div>

                            {{-- Priority --}}
                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Prioridad *</label>
                                <select wire:model="priority" class="w-full bg-white border-2 border-slate-200 rounded-lg h-11 px-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                    <option value="">Seleccione...</option>
                                    <option value="baja">Baja</option>
                                    <option value="media">Media</option>
                                    <option value="alta">Alta</option>
                                    <option value="urgente">Urgente</option>
                                </select>
                                @error('priority') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>

                            {{-- Due Date --}}
                            <div class="space-y-1">
                                <label class="text-sm font-semibold text-slate-700">Fecha de Vencimiento</label>
                                <input type="date" wire:model="dueDate" class="w-full bg-white border-2 border-slate-200 rounded-lg h-11 px-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                @error('dueDate') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        {{-- Attachments --}}
                        <div class="space-y-3">
                            <label class="text-sm font-semibold text-slate-700">Adjuntar Archivos</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                {{-- Photos --}}
                                <label class="cursor-pointer">
                                    <div class="flex items-center justify-center gap-2 border-2 border-purple-100 bg-purple-50 hover:bg-purple-100 transition-colors rounded-xl h-12 text-purple-700 font-semibold text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                                        Adjuntar Foto
                                    </div>
                                    <input type="file" wire:model="photos" multiple class="hidden" accept="image/*">
                                </label>
                                
                                {{-- Audio File (Upload) --}}
                                <label class="cursor-pointer">
                                    <div class="flex items-center justify-center gap-2 border-2 border-blue-100 bg-blue-50 hover:bg-blue-100 transition-colors rounded-xl h-12 text-blue-700 font-semibold text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                                        Subir Audio
                                    </div>
                                    <input type="file" wire:model="audio" class="hidden" accept=".mp3,.wav,.m4a,.aac,.ogg,.wma,.amr,.flac,.opus,.caf,.weba,.webm">
                                </label>

                                {{-- Live Audio Recorder (Record) --}}
                                <button type="button" @click="$dispatch('toggle-voice-recorder')" class="flex items-center justify-center gap-2 border-2 border-rose-100 bg-rose-50 hover:bg-rose-100 transition-colors rounded-xl h-12 text-rose-700 font-semibold text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v1a7 7 0 0 1-14 0v-1"/><line x1="12" x2="12" y1="19" y2="22"/></svg>
                                    Grabar Nota
                                </button>
                                
                                {{-- Document File --}}
                                <label class="cursor-pointer">
                                    <div class="flex items-center justify-center gap-2 border-2 border-emerald-100 bg-emerald-50 hover:bg-emerald-100 transition-colors rounded-xl h-12 text-emerald-700 font-semibold text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                                        Subir Documento
                                    </div>
                                    <input type="file" wire:model="documents" multiple class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt">
                                </label>
                            </div>

                            {{-- Live Audio Recorder Panel --}}
                            <div x-data="voiceRecorderComponent('audio')" 
                                 @toggle-voice-recorder.window="toggle()" 
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

                            @if($audio)
                                <div class="p-3 bg-blue-50 rounded-lg flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
                                    <span class="text-sm font-bold text-blue-800 italic">Audio adjunto listo</span>
                                    <button type="button" wire:click="$set('audio', null)" class="ml-auto text-blue-400 hover:text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    </button>
                                </div>
                            @endif
                            
                            @if($photos)
                                <div class="grid grid-cols-4 gap-2 mt-2">
                                    @foreach($photos as $photo)
                                        <div class="relative group aspect-square rounded-lg overflow-hidden border-2 border-slate-100">
                                            <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if(count($documents) > 0)
                                <div class="space-y-2 mt-2">
                                    @foreach($documents as $index => $doc)
                                        <div class="flex items-center gap-2 text-xs font-bold text-emerald-700 bg-emerald-50/50 p-2.5 rounded-lg border border-emerald-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                                            <span class="truncate flex-1">{{ $doc->getClientOriginalName() }}</span>
                                            <button type="button" wire:click="documents.splice({{ $index }}, 1)" class="text-emerald-400 hover:text-emerald-600 font-black px-1.5 py-0.5 rounded-md hover:bg-emerald-100">✕</button>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <button type="submit" wire:loading.attr="disabled" class="w-full h-14 bg-gradient-to-r from-purple-600 to-pink-600 hover:scale-[1.02] active:scale-[0.98] transition-all rounded-xl text-white font-bold text-lg shadow-lg shadow-purple-200">
                            <span wire:loading.remove>Enviar Solicitud</span>
                            <span wire:loading>Enviando...</span>
                        </button>
                    </form>
                @endif
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
    function voiceRecorderComponent(wireVarName = 'audio') {
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
    }
</script>
