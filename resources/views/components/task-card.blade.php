@props(['task'])

@php
    $priorityConfig = [
        'urgente' => ['label' => 'URGENTE', 'color' => 'bg-red-600 text-white', 'border' => 'border-l-red-600', 'bg' => 'bg-red-50'],
        'alta' => ['label' => 'ALTA', 'color' => 'bg-orange-500 text-white', 'border' => 'border-l-orange-500', 'bg' => 'bg-orange-50'],
        'media' => ['label' => 'MEDIA', 'color' => 'bg-blue-500 text-white', 'border' => 'border-l-blue-500', 'bg' => 'bg-blue-50'],
        'baja' => ['label' => 'BAJA', 'color' => 'bg-slate-400 text-white', 'border' => 'border-l-slate-400', 'bg' => 'bg-slate-50'],
    ];
    $pc = $priorityConfig[$task->priority] ?? $priorityConfig['media'];
@endphp

<div class="bg-white border-2 border-slate-200 rounded-2xl overflow-hidden hover:shadow-xl transition-all border-l-[6px] {{ $pc['border'] }}">
    <div class="flex">
        <div class="flex flex-col items-center justify-center px-4 py-4 border-r-2 border-slate-50 bg-slate-50/50 gap-1 select-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-300"><circle cx="9" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="15" cy="5" r="1"/><circle cx="15" cy="12" r="1"/><circle cx="15" cy="19" r="1"/></svg>
        </div>
        
        <div class="flex-1 p-5 space-y-3">
            <div class="flex items-start justify-between gap-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="font-bold text-lg text-slate-800">{{ $task->title }}</h3>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $pc['color'] }}">{{ $pc['label'] }}</span>
                    </div>
                    <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $task->description }}</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">
                <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Solicitado: <strong class="text-slate-700">{{ $task->requestedBy->name ?? 'N/A' }}</strong>
                </span>
                @if($task->due_date)
                    <span class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 2v4"/><path d="M16 2v4"/></svg>
                        {{ $task->due_date->format('d M Y') }}
                    </span>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-slate-100">
                @if($task->status === 'pendiente')
                    <button wire:click="updateTaskStatus('{{ $task->id }}', 'en_proceso')" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold border-2 border-blue-200 text-blue-700 hover:bg-blue-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>
                        Iniciar
                    </button>
                @endif
                @if($task->status === 'en_proceso')
                    <button wire:click="updateTaskStatus('{{ $task->id }}', 'completada')" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold border-2 border-emerald-200 text-emerald-700 hover:bg-emerald-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Completar
                    </button>
                @endif
                <a href="/tarea/{{ $task->id }}" wire:navigate class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold text-slate-600 hover:text-slate-800 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    Ver Detalles
                </a>
            </div>
        </div>
    </div>
</div>
