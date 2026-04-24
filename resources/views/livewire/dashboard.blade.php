@php
    function getPriorityColor($priority) {
        switch ($priority) {
            case 'urgente': return 'bg-red-500 text-white';
            case 'alta': return 'bg-orange-500 text-white';
            case 'media': return 'bg-blue-500 text-white';
            case 'baja': return 'bg-gray-400 text-white';
            default: return 'bg-gray-400 text-white';
        }
    }

    function getStatusColor($status) {
        switch ($status) {
            case 'completada': return 'bg-emerald-500 text-white';
            case 'en_proceso': return 'bg-blue-500 text-white';
            case 'pendiente': return 'bg-amber-500 text-white';
            case 'cancelada': return 'bg-gray-400 text-white';
            default: return 'bg-gray-100 text-gray-800';
        }
    }

    function formatStatus($s) {
        switch ($s) {
            case 'pendiente': return 'Pendiente';
            case 'en_proceso': return 'En Proceso';
            case 'completada': return 'Completada';
            case 'cancelada': return 'Cancelada';
            default: return ucfirst($s);
        }
    }

    function getStatusIcon($status) {
        switch ($status) {
            case 'completada': return '✓';
            case 'en_proceso': return '▶';
            case 'pendiente': return '○';
            case 'cancelada': return '✕';
            default: return '○';
        }
    }
@endphp

<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-blue-50 to-sky-50 p-8">
    <!-- Header Area -->
    <div class="flex items-center justify-between mb-10">
        <div>
            <h1 class="text-[32px] font-black text-slate-900">Cuadro de Mando</h1>
            <p class="text-slate-500 font-medium mt-1">{{ $isAdmin ? 'Vista de administrador' : 'Mis solicitudes' }}</p>
        </div>

        <div class="flex items-center gap-4">
            <!-- Search -->
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
                <input wire:model.live="searchQuery" type="text" placeholder="Buscar tareas..." 
                    class="w-[300px] pl-11 pr-4 py-3 bg-white border-2 border-slate-200 rounded-2xl shadow-sm focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all text-slate-900 font-medium placeholder:text-slate-400">
            </div>

            @if($isAdmin)
            <!-- Employee Filter -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <select wire:model.live="filterEmployee" 
                    class="pl-11 pr-10 py-3 bg-white border-2 border-slate-200 rounded-2xl shadow-sm focus:ring-2 focus:ring-indigo-500/20 outline-none transition-all text-slate-900 font-medium appearance-none cursor-pointer">
                    <option value="all">Todos los empleados</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-6 gap-5 mb-10">
        <!-- Total -->
        <div class="bg-white rounded-[32px] p-6 shadow-sm border-t-[6px] border-[#6366f1] transition-transform hover:scale-[1.02]">
            <div class="w-10 h-10 rounded-xl bg-[#6366f1]/10 flex items-center justify-center text-[#6366f1] mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
            </div>
            <p class="text-[32px] font-black text-[#1a2344] leading-none mb-1">{{ $stats['total'] }}</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Tareas</p>
        </div>

        <!-- Pendientes -->
        <div class="bg-white rounded-[32px] p-6 shadow-sm border-t-[6px] border-[#f59e0b] transition-transform hover:scale-[1.02]">
            <div class="w-10 h-10 rounded-xl bg-[#f59e0b]/10 flex items-center justify-center text-[#f59e0b] mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
            </div>
            <p class="text-[32px] font-black text-[#1a2344] leading-none mb-1">{{ $stats['pendiente'] }}</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Pendientes</p>
        </div>

        <!-- En Proceso -->
        <div class="bg-white rounded-[32px] p-6 shadow-sm border-t-[6px] border-[#3b82f6] transition-transform hover:scale-[1.02]">
            <div class="w-10 h-10 rounded-xl bg-[#3b82f6]/10 flex items-center justify-center text-[#3b82f6] mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            </div>
            <p class="text-[32px] font-black text-[#1a2344] leading-none mb-1">{{ $stats['en_proceso'] }}</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">En Proceso</p>
        </div>

        <!-- Completadas -->
        <div class="bg-white rounded-[32px] p-6 shadow-sm border-t-[6px] border-[#10b981] transition-transform hover:scale-[1.02]">
            <div class="w-10 h-10 rounded-xl bg-[#10b981]/10 flex items-center justify-center text-[#10b981] mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <p class="text-[32px] font-black text-[#1a2344] leading-none mb-1">{{ $stats['completada'] }}</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Completadas</p>
        </div>

        <!-- Urgentes -->
        <div class="bg-white rounded-[32px] p-6 shadow-sm border-t-[6px] border-[#ef4444] transition-transform hover:scale-[1.02]">
            <div class="w-10 h-10 rounded-xl bg-[#ef4444]/10 flex items-center justify-center text-[#ef4444] mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m19 8.5-4.5 4.5-4.5-4.5"/><path d="m19 15.5-4.5 4.5-4.5-4.5"/><path d="m10 8.5-4.5 4.5 4.5 4.5"/><path d="m10 15.5-4.5 4.5 4.5 4.5"/></svg>
            </div>
            <p class="text-[32px] font-black text-[#1a2344] leading-none mb-1">{{ $stats['urgente'] }}</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Urgentes</p>
        </div>

        <!-- Eficiencia -->
        <div class="bg-white rounded-[32px] p-6 shadow-sm border-t-[6px] border-[#a855f7] transition-transform hover:scale-[1.02]">
            <div class="w-10 h-10 rounded-xl bg-[#a855f7]/10 flex items-center justify-center text-[#a855f7] mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <p class="text-[32px] font-black text-[#1a2344] leading-none mb-1">{{ $stats['eficiencia'] }}%</p>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Eficiencia</p>
        </div>
    </div>

    <!-- Tabs Area -->
    <div class="flex items-center gap-2 mb-8 p-1.5 bg-white/80 backdrop-blur rounded-2xl shadow-sm w-fit border-2 border-slate-200">
        <button wire:click="$set('activeTab', 'list')" 
            class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'list' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" x2="21" y1="6" y2="6"/><line x1="8" x2="21" y1="12" y2="12"/><line x1="8" x2="21" y1="18" y2="18"/><line x1="3" x2="3.01" y1="6" y2="6"/><line x1="3" x2="3.01" y1="12" y2="12"/><line x1="3" x2="3.01" y1="18" y2="18"/></svg>
            Lista
        </button>
        <button wire:click="$set('activeTab', 'calendar')" 
            class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'calendar' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
            Calendario
        </button>
        <button wire:click="$set('activeTab', 'kanban')" 
            class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'kanban' ? 'bg-indigo-600 text-white shadow-lg' : 'text-slate-500 hover:bg-slate-50' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="18" x="3" y="3" rx="1"/><rect width="7" height="10" x="14" y="3" rx="1"/></svg>
            Kanban
        </button>
    </div>

    <!-- Main Content Area -->
    <div class="w-full">
        @if ($activeTab === 'list')
            <div class="space-y-6">
                <!-- Unassigned Tasks -->
                @if($unassignedTasks->count() > 0)
                <div class="bg-gradient-to-br from-orange-50 via-amber-50 to-yellow-50 rounded-[24px] shadow-lg border-2 border-orange-300 overflow-hidden">
                    <div class="h-1.5 bg-gradient-to-r from-orange-400 via-amber-500 to-yellow-500"></div>
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2.5 bg-gradient-to-br from-orange-400 to-amber-500 rounded-xl shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-orange-800 text-lg">Tareas Sin Asignar</h3>
                                <p class="text-sm text-orange-600">{{ $unassignedTasks->count() }} tareas pendientes de asignación</p>
                            </div>
                            <div class="bg-orange-500 text-white text-lg px-3 py-1 rounded-full font-bold ml-auto animate-pulse">{{ $unassignedTasks->count() }}</div>
                        </div>
                        <div class="space-y-2">
                            @foreach($unassignedTasks as $task)
                            <a href="{{ route('detalle-tarea', $task->id) }}" class="flex items-center gap-4 p-4 rounded-xl cursor-pointer transition-all group border-2 border-orange-300 bg-gradient-to-r from-orange-50 to-amber-50 hover:shadow-lg hover:border-orange-400 ring-1 ring-orange-200">
                                <div class="w-1.5 h-14 rounded-full flex-shrink-0 bg-orange-500"></div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-slate-900 truncate">{{ $task->title }}</h3>
                                    <p class="text-sm text-slate-500 truncate">{{ $task->description }}</p>
                                    <div class="flex flex-wrap items-center gap-2 mt-2">
                                        <span class="text-[10px] px-2 py-0.5 rounded-full font-bold {{ getPriorityColor($task->priority) }}">{{ ucfirst($task->priority) }}</span>
                                        <span class="text-[10px] px-2 py-0.5 rounded-full font-bold {{ getStatusColor($task->status) }}">{{ formatStatus($task->status) }}</span>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0 hidden md:block">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center bg-orange-100 border-2 border-dashed border-orange-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-500"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                                        </div>
                                        <div class="text-left">
                                            <p class="text-sm font-semibold text-orange-600">Sin asignar</p>
                                            <p class="text-[10px] text-slate-400">{{ $task->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300 group-hover:text-slate-500 transition-colors flex-shrink-0"><path d="m9 18 6-6-6-6"/></svg>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Assigned Tasks -->
                <div class="bg-white/80 backdrop-blur rounded-[24px] shadow-lg border-2 border-slate-200 overflow-hidden">
                    <div class="h-1.5 bg-gradient-to-r from-emerald-400 via-teal-500 to-cyan-500"></div>
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2.5 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-xl shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg">Tareas Asignadas</h3>
                                <p class="text-sm text-slate-500">{{ $assignedTasks->count() }} tareas con responsable</p>
                            </div>
                            <div class="bg-emerald-500 text-white text-lg px-3 py-1 rounded-full font-bold ml-auto">{{ $assignedTasks->count() }}</div>
                        </div>
                        @if($assignedTasks->count() === 0)
                        <div class="py-10 text-center border-2 border-dashed border-slate-200 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-slate-300 mb-3"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <p class="text-slate-400 font-medium">No hay tareas asignadas</p>
                        </div>
                        @else
                        <div class="space-y-2">
                            @foreach($assignedTasks as $task)
                            <a href="{{ route('detalle-tarea', $task->id) }}" class="flex items-center gap-4 p-4 rounded-xl cursor-pointer transition-all group border-2 border-slate-100 bg-white hover:shadow-md hover:border-slate-200">
                                <div class="w-1.5 h-14 rounded-full flex-shrink-0" style="background-color: {{ $task->assignedTo->color ?? '#9ca3af' }}"></div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-slate-900 truncate">{{ $task->title }}</h3>
                                    <p class="text-sm text-slate-500 truncate">{{ $task->description }}</p>
                                    <div class="flex flex-wrap items-center gap-2 mt-2">
                                        <span class="text-[10px] px-2 py-0.5 rounded-full font-bold {{ getPriorityColor($task->priority) }}">{{ ucfirst($task->priority) }}</span>
                                        <span class="text-[10px] px-2 py-0.5 rounded-full font-bold {{ getStatusColor($task->status) }}">{{ formatStatus($task->status) }}</span>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0 hidden md:block">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold" style="background-color: {{ $task->assignedTo->color ?? '#9ca3af' }}">
                                            {{ substr($task->assignedTo->name, 0, 1) }}
                                        </div>
                                        <div class="text-left">
                                            <p class="text-sm font-semibold text-slate-700">{{ $task->assignedTo->name }}</p>
                                            <p class="text-[10px] text-slate-400">{{ $task->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300 group-hover:text-slate-500 transition-colors flex-shrink-0"><path d="m9 18 6-6-6-6"/></svg>
                            </a>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        @elseif ($activeTab === 'calendar')
            <!-- Calendar View -->
            <div class="bg-white rounded-[24px] shadow-lg border-2 border-slate-200 overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-600 to-blue-600 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold capitalize text-white">
                                {{ ucfirst($currentDate->translatedFormat('F Y')) }}
                            </h2>
                            @if($filterEmployee !== 'all')
                                @php
                                    $empName = $employees->firstWhere('id', $filterEmployee)->name ?? 'Empleado';
                                @endphp
                                <p class="text-indigo-100 text-sm mt-1">Tareas de: {{ $empName }}</p>
                            @endif
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="goToToday" class="px-3 py-1.5 text-sm font-medium bg-white/20 text-white rounded-md hover:bg-white/30 transition-colors">Hoy</button>
                            <button wire:click="prevMonth" class="p-1.5 bg-white/20 text-white rounded-md hover:bg-white/30 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                            </button>
                            <button wire:click="nextMonth" class="p-1.5 bg-white/20 text-white rounded-md hover:bg-white/30 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-7 gap-1">
                        @foreach(['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'] as $dayName)
                            <div class="text-center text-xs font-bold text-indigo-600 uppercase tracking-wider py-3 border-b-2 border-indigo-100">
                                {{ $dayName }}
                            </div>
                        @endforeach

                        @for ($i = 0; $i < $adjustedFirstDay; $i++)
                            <div class="min-h-[120px] bg-gray-50/50 rounded-lg m-0.5"></div>
                        @endfor

                        @foreach ($daysInMonth as $day)
                            @php
                                $dateStr = $day->format('Y-m-d');
                                $dayTasks = $tasksByDate->get($dateStr, collect());
                                $isCurrentDay = $day->isToday();
                                $isWeekend = $day->isWeekend();
                            @endphp
                            <div class="min-h-[120px] rounded-lg m-0.5 transition-all {{ $isCurrentDay ? 'bg-indigo-50 ring-2 ring-indigo-500 shadow-md' : ($isWeekend ? 'bg-slate-50 border border-slate-200' : 'bg-white border-2 border-gray-100 hover:border-indigo-200 hover:shadow-sm') }} {{ $dayTasks->count() > 0 ? 'shadow-sm' : '' }}">
                                <div class="h-full flex flex-col p-1.5">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-bold inline-flex items-center justify-center w-7 h-7 rounded-full {{ $isCurrentDay ? 'bg-indigo-600 text-white' : ($isWeekend ? 'text-slate-400' : 'text-gray-700') }}">
                                            {{ $day->format('d') }}
                                        </span>
                                        @if($dayTasks->count() > 0)
                                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-indigo-100 text-indigo-700">{{ $dayTasks->count() }}</span>
                                        @endif
                                    </div>
                                    <div class="flex-1 space-y-1 overflow-y-auto">
                                        @foreach($dayTasks->take(4) as $task)
                                            @php
                                                $empColor = $task->assignedTo->color ?? '#9ca3af';
                                            @endphp
                                            <a href="{{ route('detalle-tarea', $task->id) }}" class="block rounded-md hover:opacity-90 transition-all hover:scale-[1.02] px-1.5 py-1" style="background-color: {{ $empColor }}18; border-left: 3px solid {{ $empColor }};" title="{{ $task->title }}">
                                                <div class="flex items-center gap-1">
                                                    <span class="text-[10px]" style="color: {{ $empColor }}">{{ getStatusIcon($task->status) }}</span>
                                                    <span class="text-[11px] font-semibold truncate flex-1" style="color: {{ $empColor }}">{{ $task->title }}</span>
                                                </div>
                                                <div class="text-[9px] truncate mt-0.5" style="color: {{ $empColor }}cc">{{ $task->assignedTo->name ?? 'Sin asignar' }}</div>
                                            </a>
                                        @endforeach
                                        @if($dayTasks->count() > 4)
                                            <div class="text-[10px] text-indigo-500 font-medium text-center py-0.5">+{{ $dayTasks->count() - 4 }} mas</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Legend -->
                <div class="mt-6 pt-6 border-t-2 border-slate-100 px-4 pb-4">
                    <div class="flex flex-wrap gap-8">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Empleados</p>
                            <div class="flex flex-wrap gap-3">
                                @php
                                    $assignedEmployees = $allTasks->whereNotNull('assigned_to_id')->pluck('assignedTo')->unique('id');
                                @endphp
                                @forelse($assignedEmployees as $employee)
                                    <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-slate-100 shadow-sm">
                                        <div class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $employee->color ?? '#9ca3af' }}"></div>
                                        <span class="text-xs font-bold text-slate-600">{{ $employee->name }}</span>
                                    </div>
                                @empty
                                    <span class="text-xs text-slate-400 font-medium italic">No hay tareas asignadas</span>
                                @endforelse
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Estado</p>
                            <div class="flex flex-wrap gap-4">
                                <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-slate-100 shadow-sm">
                                    <span class="text-sm font-black text-slate-400 leading-none">○</span>
                                    <span class="text-xs font-bold text-slate-600">Pendiente</span>
                                </div>
                                <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-slate-100 shadow-sm">
                                    <span class="text-sm font-black text-blue-500 leading-none">▶</span>
                                    <span class="text-xs font-bold text-slate-600">En Proceso</span>
                                </div>
                                <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-slate-100 shadow-sm">
                                    <span class="text-sm font-black text-emerald-500 leading-none">✓</span>
                                    <span class="text-xs font-bold text-slate-600">Completada</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @elseif ($activeTab === 'kanban')
            <!-- Kanban View -->
            @php
                $columns = [
                    ['status' => 'pendiente', 'title' => 'Pendientes', 'color' => 'text-amber-600', 'bg' => 'bg-amber-50', 'icon' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
                    ['status' => 'en_proceso', 'title' => 'En Proceso', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50', 'icon' => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>'],
                    ['status' => 'completada', 'title' => 'Completadas', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50', 'icon' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>'],
                    ['status' => 'cancelada', 'title' => 'Canceladas', 'color' => 'text-gray-600', 'bg' => 'bg-gray-50', 'icon' => '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>'],
                ];
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($columns as $column)
                    @php
                        $columnTasks = $allTasks->where('status', $column['status']);
                    @endphp
                    <div class="bg-white rounded-[24px] shadow-sm border-2 border-slate-200 flex flex-col h-full overflow-hidden">
                        <div class="p-4 {{ $column['bg'] }} border-b-2 border-slate-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $column['color'] }}">{!! $column['icon'] !!}</svg>
                                    <h3 class="font-bold text-slate-800">{{ $column['title'] }}</h3>
                                </div>
                                <span class="bg-white text-slate-600 text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">{{ $columnTasks->count() }}</span>
                            </div>
                        </div>
                        <div class="p-4 flex-1 space-y-3 bg-slate-50/50">
                            @if($columnTasks->count() === 0)
                                <div class="text-center py-8 text-sm text-slate-400 font-medium">No hay tareas</div>
                            @else
                                @foreach($columnTasks as $task)
                                    <a href="{{ route('detalle-tarea', $task->id) }}" class="block bg-white border-2 border-slate-200 rounded-xl p-3 cursor-pointer hover:shadow-md transition-all hover:border-indigo-300">
                                        <h4 class="font-semibold text-sm mb-2 text-slate-800 line-clamp-2">{{ $task->title }}</h4>
                                        <p class="text-xs text-slate-500 line-clamp-2 mb-3">{{ $task->description }}</p>
                                        <div class="flex items-center justify-between gap-2 mb-2">
                                            <span class="text-[10px] px-2 py-0.5 rounded-full font-bold {{ getPriorityColor($task->priority) }}">{{ ucfirst($task->priority) }}</span>
                                        </div>
                                        @if($task->assignedTo)
                                            <div class="flex items-center gap-1.5 mt-3 pt-3 border-t border-slate-100">
                                                <div class="w-5 h-5 rounded-full flex items-center justify-center text-white text-[10px] font-bold" style="background-color: {{ $task->assignedTo->color ?? '#9ca3af' }}">
                                                    {{ substr($task->assignedTo->name, 0, 1) }}
                                                </div>
                                                <span class="text-xs text-slate-600 font-medium truncate">{{ $task->assignedTo->name }}</span>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-1 text-xs text-orange-700 bg-orange-50 px-2 py-1 rounded-md border border-orange-200 mt-3">
                                                <span>⚠️ Sin asignar</span>
                                            </div>
                                        @endif
                                        @if($task->due_date)
                                            <div class="text-[10px] text-slate-400 mt-2 font-medium">
                                                Vence: {{ $task->due_date->format('d/m/Y') }}
                                            </div>
                                        @endif
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
