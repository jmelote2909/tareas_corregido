<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-blue-950 to-indigo-950 p-4">
        <div class="w-full max-w-md bg-white/95 backdrop-blur-sm rounded-3xl shadow-2xl overflow-hidden border-0">
            <div class="p-8">
                <div class="space-y-3 pb-6 text-center">
                    <div class="mx-auto h-16 w-16 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center shadow-lg mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                    </div>
                    <h1 class="text-2xl font-black text-slate-800">Sistema de Gestión de Tareas</h1>
                    <p class="text-slate-500 font-medium">Departamento de Infraestructura y TI</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Usuario o Correo</label>
                        <input type="text" name="email" :value="old('email')" required autofocus placeholder="admin o usuario@empresa.com" class="w-full h-11 bg-slate-50 border-2 border-slate-100 rounded-xl px-4 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-bold text-slate-700 mb-1">Contraseña</label>
                        <input type="password" name="password" required autoComplete="current-password" class="w-full h-11 bg-slate-50 border-2 border-slate-100 rounded-xl px-4 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="block mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                            <span class="ms-2 text-sm text-slate-600">Recordarme</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full h-12 bg-gradient-to-r from-blue-600 to-indigo-600 hover:scale-[1.02] active:scale-[0.98] transition-all rounded-xl text-white font-bold shadow-lg shadow-blue-200 mt-6">
                        Iniciar Sesión
                    </button>
                </form>

                <div class="pt-6 mt-6 border-t border-slate-100 space-y-3">
                    <p class="text-sm font-bold text-slate-600 text-center">Accesos rápidos</p>
                    <div class="grid gap-2">
                        <button type="button" class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:border-blue-300 hover:bg-blue-50/50 transition-all text-left">
                            <div class="h-9 w-9 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center flex-shrink-0 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800 leading-none">Administrador</p>
                                <p class="text-[10px] text-slate-500 mt-1">Gestión completa del sistema</p>
                            </div>
                        </button>
                        <button type="button" class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:border-emerald-300 hover:bg-emerald-50/50 transition-all text-left">
                            <div class="h-9 w-9 rounded-lg bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center flex-shrink-0 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800 leading-none">Operario / Empleado</p>
                                <p class="text-[10px] text-slate-500 mt-1">Ve sus tareas asignadas</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
