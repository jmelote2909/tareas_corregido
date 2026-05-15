@props(['tasks'])

@php
    $columns = [
        'pendiente' => ['title' => 'Pendiente', 'color' => 'bg-amber-500', 'border' => 'border-amber-200'],
        'en_proceso' => ['title' => 'En Proceso', 'color' => 'bg-blue-500', 'border' => 'border-blue-200'],
        'completada' => ['title' => 'Completada', 'color' => 'bg-emerald-500', 'border' => 'border-emerald-200'],
    ];
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    @foreach($columns as $status => $config)
        <div class="space-y-4">
            <div class="flex items-center justify-between px-4 py-3 bg-white rounded-xl border-2 {{ $config['border'] }} shadow-sm">
                <div class="flex items-center gap-2">
                    <div class="h-3 w-3 rounded-full {{ $config['color'] }}"></div>
                    <h3 class="font-bold text-slate-700">{{ $config['title'] }}</h3>
                </div>
                <span class="bg-slate-100 text-slate-500 text-xs font-bold px-2 py-0.5 rounded-full">
                    {{ $tasks->where('status', $status)->count() }}
                </span>
            </div>

            <div class="space-y-3 min-h-[500px]">
                @foreach($tasks->where('status', $status) as $task)
                    <a href="/tarea/{{ $task->id }}" class="block bg-white p-4 rounded-xl border-2 border-slate-100 shadow-sm hover:shadow-md hover:border-slate-200 transition-all group">
                        <div class="flex justify-between items-start mb-2">
                            @php
                                $priorityColor = match($task->priority) {
                                    'urgente' => 'bg-red-100 text-red-600',
                                    'alta' => 'bg-orange-100 text-orange-600',
                                    'media' => 'bg-blue-100 text-blue-600',
                                    'baja' => 'bg-slate-100 text-slate-600',
                                    default => 'bg-slate-100 text-slate-600'
                                };
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $priorityColor }}">{{ strtoupper($task->priority) }}</span>
                            <div class="flex -space-x-2">
                                @if($task->assignedTo)
                                    <div class="h-6 w-6 rounded-full border-2 border-white flex items-center justify-center text-[10px] font-bold text-white shadow-sm" style="background-color: {{ $task->assignedTo->color }}">
                                        {{ substr($task->assignedTo->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm group-hover:text-indigo-600 transition-colors">{{ $task->title }}</h4>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $task->description }}</p>
                        <div class="mt-3 pt-3 border-t border-slate-50 flex items-center justify-between">
                            <div class="flex items-center gap-1 text-[10px] text-slate-400 font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                {{ $task->requestedBy->name ?? 'N/A' }}
                            </div>
                            @if($task->due_date)
                                <div class="flex items-center gap-1 text-[10px] text-slate-400 font-medium">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 2v4"/><path d="M16 2v4"/></svg>
                                    {{ $task->due_date->format('d/m') }}
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
