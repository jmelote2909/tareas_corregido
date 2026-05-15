@props(['task', 'highlighted' => false])

@php
    $empColor = $task->assignedTo->color ?? '#9ca3af';
    $formatStatus = function($s) {
        return match($s) {
            'pendiente' => 'Pendiente',
            'en_proceso' => 'En Proceso',
            'completada' => 'Completada',
            'cancelada' => 'Cancelada',
            default => $s
        };
    };
    $getPriorityColor = function($p) {
        return match($p) {
            'urgente' => 'bg-red-500 text-white',
            'alta' => 'bg-orange-500 text-white',
            'media' => 'bg-blue-500 text-white',
            'baja' => 'bg-gray-400 text-white',
            default => 'bg-gray-400 text-white'
        };
    };
    $getStatusColor = function($s) {
        return match($s) {
            'completada' => 'bg-emerald-500 text-white',
            'en_proceso' => 'bg-blue-500 text-white',
            'pendiente' => 'bg-amber-500 text-white',
            'cancelada' => 'bg-gray-400 text-white',
            default => 'bg-gray-100 text-gray-800'
        };
    };
@endphp

<div 
    class="flex items-center gap-4 p-4 rounded-xl cursor-pointer transition-all group {{ $highlighted ? 'border-2 border-orange-300 bg-gradient-to-r from-orange-50 to-amber-50 hover:shadow-lg hover:border-orange-400 ring-1 ring-orange-200' : 'border-2 border-slate-100 bg-white hover:shadow-md hover:border-slate-200' }}"
    onclick="window.location.href='/tarea/{{ $task->id }}'"
>
    <div class="w-1.5 h-14 rounded-full flex-shrink-0" style="background-color: {{ $highlighted ? '#f97316' : $empColor }}"></div>

    <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2 mb-1">
            <h3 class="font-bold text-slate-900 truncate">{{ $task->title }}</h3>
            {{-- Icons for attachments could be added here --}}
        </div>
        <p class="text-sm text-slate-500 truncate">{{ $task->description }}</p>
        <div class="flex flex-wrap items-center gap-2 mt-2">
            <span class="text-[10px] px-2 py-0 h-5 flex items-center rounded-full font-bold {{ $getPriorityColor($task->priority) }}">{{ ucfirst($task->priority) }}</span>
            <span class="text-[10px] px-2 py-0 h-5 flex items-center rounded-full font-bold {{ $getStatusColor($task->status) }}">{{ $formatStatus($task->status) }}</span>
            <span class="text-[10px] text-slate-400 capitalize">{{ $task->category }}</span>
        </div>
    </div>

    <div class="text-right flex-shrink-0 hidden md:block">
        @if($task->assignedTo)
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold" style="background-color: {{ $empColor }}">
                    {{ substr($task->assignedTo->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-700">{{ $task->assignedTo->name }}</p>
                    <p class="text-[10px] text-slate-400">
                        {{ $task->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        @else
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full flex items-center justify-center bg-orange-100 border-2 border-dashed border-orange-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-orange-500"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-orange-600">Sin asignar</p>
                    <p class="text-[10px] text-slate-400">
                        {{ $task->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
        @endif
    </div>

    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300 group-hover:text-slate-500 transition-colors flex-shrink-0"><path d="m9 18 6-6-6-6"/></svg>
</div>
