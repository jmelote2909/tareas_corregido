@props(['tasks'])

@php
    $currentDate = now();
    $startOfMonth = $currentDate->copy()->startOfMonth();
    $endOfMonth = $currentDate->copy()->endOfMonth();
    $daysInMonth = $startOfMonth->daysInMonth;
    $firstDayOfWeek = $startOfMonth->dayOfWeek; // 0 (Sun) to 6 (Sat)
    // Adjust to Monday start if needed, but let's keep it simple
@endphp

<div class="bg-white rounded-2xl border-2 border-slate-100 shadow-xl overflow-hidden">
    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 text-white px-6 py-4 flex items-center justify-between">
        <h3 class="text-xl font-bold capitalize">{{ $currentDate->translatedFormat('F Y') }}</h3>
        <div class="flex gap-2">
            <span class="bg-white/20 px-3 py-1 rounded-full text-xs font-bold">{{ $tasks->whereNotNull('due_date')->count() }} Tareas</span>
        </div>
    </div>
    
    <div class="p-4">
        <div class="grid grid-cols-7 gap-1">
            @foreach(['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'] as $day)
                <div class="text-center text-[10px] font-bold text-slate-400 uppercase py-2">{{ $day }}</div>
            @endforeach

            @for($i = 0; $i < $firstDayOfWeek; $i++)
                <div class="aspect-square bg-slate-50/50 rounded-lg"></div>
            @endfor

            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $date = $startOfMonth->copy()->day($day);
                    $dayTasks = $tasks->filter(fn($t) => $t->due_date && $t->due_date->isSameDay($date));
                    $isToday = $date->isToday();
                @endphp
                <div class="aspect-square rounded-lg border-2 {{ $isToday ? 'border-indigo-500 bg-indigo-50' : 'border-slate-50 hover:border-slate-200' }} p-1 transition-all">
                    <div class="flex justify-between items-start">
                        <span class="text-[10px] font-bold {{ $isToday ? 'text-indigo-600' : 'text-slate-500' }}">{{ $day }}</span>
                        @if($dayTasks->count() > 0)
                            <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                        @endif
                    </div>
                    <div class="mt-1 space-y-0.5 overflow-hidden">
                        @foreach($dayTasks->take(2) as $task)
                            <div class="text-[8px] leading-tight truncate px-1 rounded {{ match($task->status) { 'completada' => 'bg-emerald-100 text-emerald-700', 'en_proceso' => 'bg-blue-100 text-blue-700', default => 'bg-amber-100 text-amber-700' } }}">
                                {{ $task->title }}
                            </div>
                        @endforeach
                        @if($dayTasks->count() > 2)
                            <div class="text-[8px] text-slate-400 text-center font-bold">+{{ $dayTasks->count() - 2 }}</div>
                        @endif
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>
