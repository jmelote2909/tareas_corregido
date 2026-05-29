<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-blue-50 to-sky-50 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950 py-12">
    <div class="max-w-2xl mx-auto px-4">
        <!-- Breadcrumb / Header -->
        <div class="flex items-center gap-3 mb-8">
            <div class="h-12 w-12 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            </div>
            <div>
                <h1 class="text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight">Ajustes del Sistema</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Configuración global del servidor de correo para notificaciones.</p>
            </div>
        </div>

        <!-- Success Message -->
        @if ($successMessage)
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/50 rounded-2xl flex items-center gap-3 text-emerald-800 dark:text-emerald-300 shadow-sm animate-pulse">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span class="font-medium text-sm">{{ $successMessage }}</span>
            </div>
        @endif

        <!-- Card Container -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-xl shadow-slate-100 dark:shadow-none overflow-hidden">
            <div class="p-8 space-y-6">
                <!-- Info Alert -->
                <div class="p-4 bg-indigo-50 dark:bg-indigo-950/20 border border-indigo-100 dark:border-indigo-900/50 rounded-2xl flex gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-indigo-600 dark:text-indigo-400 shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="16" y2="12"/><line x1="12" x2="12.01" y1="8" y2="8"/></svg>
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-indigo-950 dark:text-indigo-300 uppercase tracking-wider">Configuración de Envío</p>
                        <p class="text-xs text-indigo-700 dark:text-indigo-400 leading-relaxed">
                            Aquí puedes cambiar las credenciales para el envío de correos. Si usas Gmail, recuerda que debes generar una <strong>Contraseña de Aplicación de 16 caracteres</strong> en la seguridad de tu cuenta de Google.
                        </p>
                    </div>
                </div>

                <form wire:submit.prevent="save" class="space-y-6">
                    <!-- Mailer Type Select -->
                    <div class="space-y-2">
                        <label for="mailMailer" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Controlador de Correo</label>
                        <div class="relative">
                            <select 
                                wire:model.live="mailMailer" 
                                id="mailMailer"
                                class="w-full px-4 py-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-800 dark:text-slate-100 text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none appearance-none"
                            >
                                <option value="smtp">SMTP tradicional (ej: Gmail)</option>
                                <option value="resend">Resend API Key (Configuración por defecto)</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </div>
                        </div>
                    </div>

                    @if ($mailMailer === 'smtp')
                        <!-- SMTP Fields -->
                        <div class="space-y-4 animate-fadeIn">
                            <!-- Gmail Account Field -->
                            <div class="space-y-2">
                                <label for="mailUsername" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Cuenta de Correo (Remitente)</label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                    </div>
                                    <input 
                                        type="email" 
                                        wire:model="mailUsername" 
                                        id="mailUsername" 
                                        placeholder="ejemplo@gmail.com"
                                        class="w-full pl-12 pr-4 py-3 bg-slate-50 dark:bg-slate-950 border @error('mailUsername') border-red-500 @else border-slate-200 dark:border-slate-800 @enderror rounded-2xl text-slate-800 dark:text-slate-100 text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none"
                                    />
                                </div>
                                @error('mailUsername')
                                    <span class="text-xs text-red-500 font-medium block ml-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Gmail App Password Field -->
                            <div class="space-y-2">
                                <label for="mailPassword" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Contraseña de Aplicación de 16 caracteres</label>
                                <div class="relative">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    </div>
                                    <input 
                                        type="password" 
                                        wire:model="mailPassword" 
                                        id="mailPassword" 
                                        placeholder="•••• •••• •••• ••••"
                                        class="w-full pl-12 pr-4 py-3 bg-slate-50 dark:bg-slate-950 border @error('mailPassword') border-red-500 @else border-slate-200 dark:border-slate-800 @enderror rounded-2xl text-slate-800 dark:text-slate-100 text-sm font-medium focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all outline-none"
                                    />
                                </div>
                                @error('mailPassword')
                                    <span class="text-xs text-red-500 font-medium block ml-1">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @else
                        <!-- Resend Info Panel -->
                        <div class="p-4 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-850 rounded-2xl space-y-2 animate-fadeIn">
                            <p class="text-xs font-bold text-slate-800 dark:text-slate-300 uppercase tracking-wider">Configuración de Resend</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                                Actualmente Resend se está cargando con la clave por defecto definida de forma segura en tu servidor. No requiere configuraciones de contraseñas de aplicación adicionales, pero recuerda tener tu dominio verificado para envíos a correos externos.
                            </p>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-slate-150 dark:border-slate-800 flex justify-end">
                        <button 
                            type="submit"
                            class="px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white rounded-2xl font-bold text-sm shadow-lg shadow-indigo-500/25 transition-all transform hover:-translate-y-0.5 active:translate-y-0 cursor-pointer"
                        >
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
