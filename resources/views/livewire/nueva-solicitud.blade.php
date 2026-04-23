<div class="min-h-screen bg-gradient-to-br from-purple-50 via-pink-50 to-blue-50 py-8">
    <div class="container mx-auto px-4 max-w-2xl">
        <div class="mb-6">
            <a href="{{ route('dashboard') }}" wire:navigate class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors">
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
                                    <label class="text-sm font-semibold text-slate-700">Nombre *</label>
                                    <input type="text" wire:model="requesterName" class="w-full bg-white border-2 border-blue-100 rounded-lg h-10 px-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-sm font-semibold text-slate-700">Departamento *</label>
                                    <select wire:model="requesterDepartment" class="w-full bg-white border-2 border-blue-100 rounded-lg h-10 px-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                        <option value="">Seleccione...</option>
                                        <option value="produccion">Producción</option>
                                        <option value="calidad">Calidad</option>
                                        <option value="logistica">Logística</option>
                                        <option value="administracion">Administración</option>
                                    </select>
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
                                <select wire:model="category" class="w-full bg-white border-2 border-slate-200 rounded-lg h-11 px-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                                    <option value="">Seleccione...</option>
                                    <option value="infraestructura">Infraestructura</option>
                                    <option value="ti">Tecnología de Información</option>
                                    <option value="mantenimiento">Mantenimiento</option>
                                </select>
                                @error('category') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
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
                        </div>

                        {{-- Attachments --}}
                        <div class="space-y-3">
                            <label class="text-sm font-semibold text-slate-700">Adjuntar Archivos</label>
                            <div class="flex gap-3">
                                <label class="flex-1 cursor-pointer">
                                    <div class="flex items-center justify-center gap-2 border-2 border-purple-100 bg-purple-50 hover:bg-purple-100 transition-colors rounded-xl h-12 text-purple-700 font-semibold">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                                        Adjuntar Foto
                                    </div>
                                    <input type="file" wire:model="photos" multiple class="hidden">
                                </label>
                                <div class="flex-1 flex items-center justify-center gap-2 border-2 border-blue-100 bg-blue-50 hover:bg-blue-100 transition-colors rounded-xl h-12 text-blue-700 font-semibold cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v1a7 7 0 0 1-14 0v-1"/><line x1="12" x2="12" y1="19" y2="22"/></svg>
                                    Grabar Audio
                                </div>
                            </div>
                            
                            @if($photos)
                                <div class="grid grid-cols-4 gap-2 mt-2">
                                    @foreach($photos as $photo)
                                        <div class="relative group aspect-square rounded-lg overflow-hidden border-2 border-slate-100">
                                            <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
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
</div>
