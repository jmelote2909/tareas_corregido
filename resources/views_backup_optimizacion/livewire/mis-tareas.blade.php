<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 py-8">
    <div class="container mx-auto px-4">
        @if($showSelector || !$selectedEmployee)
            {{-- Employee Selector --}}
            <div class="max-w-2xl mx-auto space-y-6">
                <div class="text-center space-y-2">
                    <div class="mx-auto h-20 w-20 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <h1 class="text-3xl font-bold text-slate-800">Selecciona tu Perfil</h1>
                    <p class="text-slate-500">Elige tu nombre para ver tus tareas asignadas</p>
                </div>
                <div class="grid gap-3">
                    @foreach($employees as $emp)
                        <button
                            wire:click="selectEmployee('{{ $emp->id }}')"
                            class="flex items-center gap-4 p-5 bg-white rounded-2xl border-2 border-slate-200 hover:border-emerald-400 hover:shadow-xl transition-all text-left group"
                        >
                            <div
                                class="h-16 w-16 rounded-2xl flex items-center justify-center text-white text-xl font-bold shadow-md flex-shrink-0"
                                style="background-color: {{ $emp->color }}"
                            >
                                {{ substr($emp->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-slate-800 text-lg group-hover:text-emerald-700 transition-colors">{{ $emp->name }}</p>
                                <p class="text-sm text-slate-500">{{ $emp->role }}</p>
                                <p class="text-xs text-slate-400">{{ $emp->email }}</p>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300 group-hover:text-emerald-500 transition-colors"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </button>
                    @endforeach
                </div>
            </div>
        @else
            {{-- Main Content --}}
            <div class="space-y-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div
                            class="h-16 w-16 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg"
                            style="background-color: {{ $selectedEmployee->color }}"
                        >
                            {{ substr($selectedEmployee->name, 0, 1) }}
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-slate-800">Mis Tareas</h1>
                            <p class="text-slate-500">
                                {{ $selectedEmployee->name }} - {{ $selectedEmployee->role }}
                                @if(auth()->user()->role === 'admin')
                                    <button wire:click="$set('showSelector', true)" class="ml-2 text-teal-600 hover:underline text-sm">(cambiar)</button>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Stats Cards --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 border-2 border-amber-300 rounded-2xl p-4 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-600 mx-auto mb-1"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <p class="text-3xl font-bold text-amber-800">{{ $pendingTasks->count() }}</p>
                        <p class="text-sm text-amber-600 font-medium">Pendientes</p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-300 rounded-2xl p-4 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600 mx-auto mb-1"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                        <p class="text-3xl font-bold text-blue-800">{{ $inProgressTasks->count() }}</p>
                        <p class="text-sm text-blue-600 font-medium">En Proceso</p>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 border-2 border-emerald-300 rounded-2xl p-4 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600 mx-auto mb-1"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <p class="text-3xl font-bold text-emerald-800">{{ $completedTasks->count() }}</p>
                        <p class="text-sm text-emerald-600 font-medium">Completadas</p>
                    </div>
                    <div class="bg-gradient-to-br from-teal-50 to-teal-100 border-2 border-teal-300 rounded-2xl p-4 flex flex-col justify-center">
                        <p class="text-sm font-bold text-teal-700 text-center mb-1">Progreso</p>
                        <div class="w-full bg-teal-200 rounded-full h-3 mb-1">
                            @php
                                $total = $pendingTasks->count() + $inProgressTasks->count() + $completedTasks->count();
                                $percent = $total > 0 ? round(($completedTasks->count() / $total) * 100) : 0;
                            @endphp
                            <div class="bg-teal-600 h-3 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                        <p class="text-center text-xl font-bold text-teal-800">{{ $percent }}%</p>
                    </div>
                </div>

                {{-- Tabs --}}
                <div class="space-y-4">
                    <div class="bg-white border-2 border-slate-200 p-1 rounded-xl inline-flex gap-1">
                        <button wire:click="$set('viewTab', 'pendiente')" class="px-6 py-2 rounded-lg text-sm font-bold transition-all {{ $viewTab === 'pendiente' ? 'bg-amber-500 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                            Pendientes ({{ $pendingTasks->count() }})
                        </button>
                        <button wire:click="$set('viewTab', 'en_proceso')" class="px-6 py-2 rounded-lg text-sm font-bold transition-all {{ $viewTab === 'en_proceso' ? 'bg-blue-500 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                            En Proceso ({{ $inProgressTasks->count() }})
                        </button>
                        <button wire:click="$set('viewTab', 'completada')" class="px-6 py-2 rounded-lg text-sm font-bold transition-all {{ $viewTab === 'completada' ? 'bg-emerald-500 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                            Completadas ({{ $completedTasks->count() }})
                        </button>
                    </div>

                    <div class="space-y-3">
                        @php
                            $currentTasks = match($viewTab) {
                                'pendiente' => $pendingTasks,
                                'en_proceso' => $inProgressTasks,
                                'completada' => $completedTasks,
                            };
                        @endphp

                        @if($currentTasks->count() === 0)
                            <div class="bg-white border-2 border-dashed border-slate-200 rounded-2xl p-12 text-center">
                                <p class="text-slate-400 font-medium">No hay tareas en esta categoría</p>
                            </div>
                        @else
                            @foreach($currentTasks as $task)
                                <x-task-card :task="$task" />
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
