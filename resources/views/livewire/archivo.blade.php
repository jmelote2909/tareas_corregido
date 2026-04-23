<div class="p-8 bg-[#f8f9fc] min-h-screen">
    <!-- Header Area -->
    <div class="flex items-center justify-between mb-10">
        <div class="flex items-center gap-6">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-orange-400 to-amber-500 flex items-center justify-center text-white shadow-lg shadow-orange-200">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 3H2l8 9v6l4 3v-9L22 3z"/></svg>
            </div>
            <div>
                <h1 class="text-[32px] font-black text-[#1a2344]">Archivo Historico</h1>
                <p class="text-slate-400 font-medium mt-1">Tareas completadas hace mas de 30 dias</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="bg-amber-100/50 px-4 py-2 rounded-xl flex items-center gap-2 border border-amber-200/50">
                <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div>
                <span class="text-xs font-black text-amber-700 uppercase tracking-widest">{{ $archivedTasks->count() }} tareas archivadas</span>
            </div>
            <button class="flex items-center gap-2 px-5 py-2.5 bg-white rounded-xl text-slate-600 font-bold shadow-sm border border-slate-100 hover:bg-slate-50 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M3 21v-5h5"/></svg>
                Actualizar
            </button>
        </div>
    </div>

    <!-- Filters Area -->
    <div class="bg-[#fffdf0] rounded-[40px] p-8 shadow-sm border border-amber-100/50 mb-10">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
            </div>
            <h3 class="text-sm font-black text-amber-900 uppercase tracking-widest">Filtros de busqueda</h3>
        </div>

        <div class="grid grid-cols-4 gap-6">
            <!-- Search -->
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-amber-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
                <input wire:model.live="searchQuery" type="text" placeholder="Buscar en archivo..." 
                    class="w-full pl-11 pr-4 py-3 bg-white border border-amber-100 rounded-2xl outline-none focus:ring-4 focus:ring-amber-500/5 focus:border-amber-400 transition-all text-[#1a2344] font-medium placeholder:text-amber-200">
            </div>

            <!-- Employee -->
            <div class="relative">
                <select wire:model.live="filterEmployee" class="w-full pl-4 pr-10 py-3 bg-white border border-amber-100 rounded-2xl outline-none focus:ring-4 focus:ring-amber-500/5 focus:border-amber-400 transition-all text-[#1a2344] font-medium appearance-none cursor-pointer">
                    <option value="all">Todos los empleados</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-amber-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </div>
            </div>

            <!-- Category -->
            <div class="relative">
                <select wire:model.live="filterCategory" class="w-full pl-4 pr-10 py-3 bg-white border border-amber-100 rounded-2xl outline-none focus:ring-4 focus:ring-amber-500/5 focus:border-amber-400 transition-all text-[#1a2344] font-medium appearance-none cursor-pointer">
                    <option value="all">Todas las categorias</option>
                    <option value="Infraestructura">Infraestructura</option>
                    <option value="TI">TI</option>
                    <option value="Mantenimiento">Mantenimiento</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-amber-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </div>
            </div>

            <!-- Year -->
            <div class="relative">
                <select wire:model.live="filterYear" class="w-full pl-4 pr-10 py-3 bg-white border border-amber-100 rounded-2xl outline-none focus:ring-4 focus:ring-amber-500/5 focus:border-amber-400 transition-all text-[#1a2344] font-medium appearance-none cursor-pointer">
                    <option value="all">Todos los años</option>
                    <option value="2026">2026</option>
                    <option value="2025">2025</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-amber-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    <div class="bg-white rounded-[40px] shadow-sm border-2 border-dashed border-amber-200 p-24 flex flex-col items-center justify-center relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-amber-50/20 to-transparent pointer-events-none"></div>
        
        <div class="w-20 h-20 rounded-3xl bg-amber-50 flex items-center justify-center text-amber-400 mb-8 relative">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 20a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-7.9a2 2 0 0 1-1.69-.9L9.6 3.9A2 2 0 0 0 7.93 3H4a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2Z"/><path d="M12 12v4"/><path d="M10 14h4"/></svg>
        </div>
        
        <h3 class="text-2xl font-black text-amber-900 mb-3 relative">Archivo vacío</h3>
        <p class="text-amber-600/60 font-medium text-center max-w-md relative">
            Las tareas completadas se archivan automáticamente después de 30 días. Aquí se guardará el histórico completo.
        </p>
    </div>
</div>
