<div class="p-8 bg-[#f8f9fc] min-h-screen">
    <!-- Header Area -->
    <div class="mb-10">
        <h1 class="text-[32px] font-black text-[#1a2344]">Informes y Analisis</h1>
        <p class="text-slate-400 font-medium mt-1">Estadisticas detalladas de tareas completadas</p>
    </div>

    <!-- Time Range Selector -->
    <div class="bg-white rounded-[32px] p-4 shadow-sm mb-10 flex items-center justify-between">
        <div class="flex items-center gap-1 bg-slate-50 p-1.5 rounded-2xl">
            @foreach(['Dia', 'Semana', 'Mes', 'Año'] as $range)
                <button wire:click="setRange('{{ $range }}')" 
                    class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all {{ $timeRange === $range ? 'bg-[#3b49ff] text-white shadow-lg' : 'text-slate-400 hover:bg-white hover:text-slate-600' }}">
                    <div class="flex items-center gap-2">
                        @if($range === 'Dia') <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg> @endif
                        @if($range === 'Semana') <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10h18"/><path d="M3 14h18"/><path d="M3 18h18"/><path d="M7 6v14"/><path d="M11 6v14"/><path d="M15 6v14"/><path d="M19 6v14"/></svg> @endif
                        @if($range === 'Mes') <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg> @endif
                        @if($range === 'Año') <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg> @endif
                        {{ $range }}
                    </div>
                </button>
            @endforeach
        </div>

        <div class="flex items-center gap-4">
            <button class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <span class="text-[#1a2344] font-black tracking-tight">20 Abr - 26 Abr 2026</span>
            <button class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-slate-100 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-9-6"/></svg>
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-4 gap-6 mb-10">
        <!-- Completados -->
        <div class="bg-[#eef2ff] rounded-[32px] p-8 border border-indigo-100/50 shadow-sm relative overflow-hidden group transition-all hover:scale-[1.02]">
            <div class="flex items-start justify-between">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-500 mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg>
                    </div>
                    <p class="text-[44px] font-black text-[#1a2344] leading-none mb-2">{{ $stats['completados'] }}</p>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Completados</p>
                </div>
                <span class="text-[10px] font-black text-emerald-500">+0%</span>
            </div>
        </div>

        <!-- Creados -->
        <div class="bg-[#fffbeb] rounded-[32px] p-8 border border-amber-100/50 shadow-sm transition-all hover:scale-[1.02]">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-600 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 7V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2"/><path d="M21 12H3"/><path d="M18 12c0-1.66-1.34-3-3-3s-3 1.34-3 3 1.34 3 3 3 3-1.34 3-3Z"/></svg>
            </div>
            <p class="text-[44px] font-black text-[#1a2344] leading-none mb-2">{{ $stats['creados'] }}</p>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Creados en el periodo</p>
        </div>

        <!-- Tiempo Medio -->
        <div class="bg-[#f0fdf4] rounded-[32px] p-8 border border-emerald-100/50 shadow-sm transition-all hover:scale-[1.02]">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
            </div>
            <p class="text-[44px] font-black text-[#1a2344] leading-none mb-2">{{ $stats['tiempo_medio'] }} dias</p>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Tiempo medio resolucion</p>
        </div>

        <!-- Urgentes -->
        <div class="bg-[#fef2f2] rounded-[32px] p-8 border border-red-100/50 shadow-sm transition-all hover:scale-[1.02]">
            <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center text-red-600 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg>
            </div>
            <p class="text-[44px] font-black text-[#1a2344] leading-none mb-2">{{ $stats['urgentes_resueltos'] }}</p>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Urgentes / Altos resueltos</p>
        </div>
    </div>

    <!-- Charts Area -->
    <div class="grid grid-cols-2 gap-8 mb-8">
        <!-- Rendimiento -->
        <div class="bg-white rounded-[40px] shadow-sm p-8 border border-slate-100">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-indigo-900 leading-tight">Rendimiento por Empleado</h3>
                    <p class="text-xs text-slate-400 font-medium">Tareas completadas por cada miembro</p>
                </div>
            </div>
            <div class="h-64 flex items-center justify-center border-2 border-dashed border-slate-50 rounded-3xl">
                <p class="text-slate-300 font-bold">No hay datos para este periodo</p>
            </div>
        </div>

        <!-- Por Departamento -->
        <div class="bg-white rounded-[40px] shadow-sm p-8 border border-slate-100">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 16v-4"/><path d="M11 16V9"/><path d="M15 16V5"/><path d="M19 16V12"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-purple-900 leading-tight">Por Departamento Solicitante</h3>
                    <p class="text-xs text-slate-400 font-medium">Tareas resueltas para cada departamento</p>
                </div>
            </div>
            <div class="h-64 flex items-center justify-center border-2 border-dashed border-slate-50 rounded-3xl">
                <p class="text-slate-300 font-bold">No hay datos para este periodo</p>
            </div>
        </div>
    </div>

    <!-- Bottom Distribution Grid -->
    <div class="grid grid-cols-2 gap-8">
        <!-- Prioridad -->
        <div class="bg-white rounded-[40px] shadow-sm p-8 border border-slate-100">
            <h3 class="text-lg font-black text-red-600 mb-2">Distribucion por Prioridad</h3>
            <p class="text-xs text-slate-400 font-medium mb-8">Tareas completadas según nivel de urgencia</p>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 rounded-[24px] bg-red-50 border border-red-100/50">
                    <p class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-1">Urgente</p>
                    <p class="text-2xl font-black text-red-600">0</p>
                </div>
                <div class="p-4 rounded-[24px] bg-orange-50 border border-orange-100/50">
                    <p class="text-[10px] font-black text-orange-400 uppercase tracking-widest mb-1">Alta</p>
                    <p class="text-2xl font-black text-orange-600">0</p>
                </div>
                <div class="p-4 rounded-[24px] bg-amber-50 border border-amber-100/50">
                    <p class="text-[10px] font-black text-amber-400 uppercase tracking-widest mb-1">Media</p>
                    <p class="text-2xl font-black text-amber-600">0</p>
                </div>
                <div class="p-4 rounded-[24px] bg-blue-50 border border-blue-100/50">
                    <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-1">Baja</p>
                    <p class="text-2xl font-black text-blue-600">0</p>
                </div>
            </div>
        </div>

        <!-- Categoria -->
        <div class="bg-white rounded-[40px] shadow-sm p-8 border border-slate-100">
            <h3 class="text-lg font-black text-teal-600 mb-2">Distribucion por Categoría</h3>
            <p class="text-xs text-slate-400 font-medium mb-8">Tipo de trabajo realizado</p>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 rounded-[24px] bg-indigo-50 border border-indigo-100/50">
                    <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Infraestructura</p>
                    <p class="text-2xl font-black text-indigo-600">0</p>
                </div>
                <div class="p-4 rounded-[24px] bg-teal-50 border border-teal-100/50">
                    <p class="text-[10px] font-black text-teal-400 uppercase tracking-widest mb-1">TI</p>
                    <p class="text-2xl font-black text-teal-600">0</p>
                </div>
                <div class="p-4 rounded-[24px] bg-orange-50 border border-orange-100/50">
                    <p class="text-[10px] font-black text-orange-400 uppercase tracking-widest mb-1">Mantenimiento</p>
                    <p class="text-2xl font-black text-orange-600">0</p>
                </div>
                <div class="p-4 rounded-[24px] bg-slate-50 border border-slate-100/50">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Otro</p>
                    <p class="text-2xl font-black text-slate-600">0</p>
                </div>
            </div>
        </div>
    </div>
</div>
