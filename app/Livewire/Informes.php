<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\Employee;
use Livewire\Component;

class Informes extends Component
{
    public $timeRange = 'Semana';
    public $startDate;
    public $endDate;

    public function mount()
    {
        $this->updateDates();
    }

    public function setRange($range)
    {
        $this->timeRange = $range;
        $this->updateDates();
    }

    public function previous()
    {
        $start = \Carbon\Carbon::parse($this->startDate);
        switch ($this->timeRange) {
            case 'Dia':
                $this->startDate = $start->subDay()->startOfDay();
                $this->endDate = \Carbon\Carbon::parse($this->startDate)->endOfDay();
                break;
            case 'Semana':
                $this->startDate = $start->subWeek()->startOfWeek();
                $this->endDate = \Carbon\Carbon::parse($this->startDate)->endOfWeek();
                break;
            case 'Mes':
                $this->startDate = $start->subMonth()->startOfMonth();
                $this->endDate = \Carbon\Carbon::parse($this->startDate)->endOfMonth();
                break;
            case 'Año':
                $this->startDate = $start->subYear()->startOfYear();
                $this->endDate = \Carbon\Carbon::parse($this->startDate)->endOfYear();
                break;
        }
    }

    public function next()
    {
        $start = \Carbon\Carbon::parse($this->startDate);
        switch ($this->timeRange) {
            case 'Dia':
                $this->startDate = $start->addDay()->startOfDay();
                $this->endDate = \Carbon\Carbon::parse($this->startDate)->endOfDay();
                break;
            case 'Semana':
                $this->startDate = $start->addWeek()->startOfWeek();
                $this->endDate = \Carbon\Carbon::parse($this->startDate)->endOfWeek();
                break;
            case 'Mes':
                $this->startDate = $start->addMonth()->startOfMonth();
                $this->endDate = \Carbon\Carbon::parse($this->startDate)->endOfMonth();
                break;
            case 'Año':
                $this->startDate = $start->addYear()->startOfYear();
                $this->endDate = \Carbon\Carbon::parse($this->startDate)->endOfYear();
                break;
        }
    }

    protected function updateDates()
    {
        switch ($this->timeRange) {
            case 'Dia':
                $this->startDate = now()->startOfDay();
                $this->endDate = now()->endOfDay();
                break;
            case 'Semana':
                $this->startDate = now()->startOfWeek();
                $this->endDate = now()->endOfWeek();
                break;
            case 'Mes':
                $this->startDate = now()->startOfMonth();
                $this->endDate = now()->endOfMonth();
                break;
            case 'Año':
                $this->startDate = now()->startOfYear();
                $this->endDate = now()->endOfYear();
                break;
        }
    }

    public function render()
    {
        $start = \Carbon\Carbon::parse($this->startDate);
        $end = \Carbon\Carbon::parse($this->endDate);
        $user = auth()->user();
        $isAdmin = $user->role === 'admin';
        $employeeId = null;

        if (!$isAdmin) {
            $employee = Employee::where('user_id', $user->id)->first();
            $employeeId = $employee ? $employee->id : 'none';
        }

        // Tareas creadas en el periodo
        $createdTasksQuery = Task::whereBetween('created_at', [$start, $end]);
        if (!$isAdmin) {
            $createdTasksQuery->where('assigned_to_id', $employeeId);
        }
        $createdTasks = $createdTasksQuery->get();
        
        // Tareas completadas en el periodo (usando completed_at)
        $completedTasksQuery = Task::where('status', 'completada')
            ->whereBetween('completed_at', [$start, $end]);
        if (!$isAdmin) {
            $completedTasksQuery->where('assigned_to_id', $employeeId);
        }
        $completedTasks = $completedTasksQuery->get();

        $stats = [
            'completados' => $completedTasks->count(),
            'creados' => $createdTasks->count(),
            'tiempo_medio' => round($completedTasks->avg(function($task) {
                return $task->created_at->diffInDays($task->completed_at ?? $task->updated_at);
            }) ?? 0, 1),
            'urgentes_resueltos' => $completedTasks->whereIn('priority', ['alta', 'urgente'])->count(),
        ];

        // Group by employee
        $performanceByEmployee = $completedTasks->groupBy('assigned_to_id')
            ->map(function($tasks) {
                return [
                    'name' => $tasks->first()->assignedTo->name ?? 'Desconocido',
                    'count' => $tasks->count()
                ];
            });

        // Distribution by priority
        $priorityDistribution = [
            'urgente' => $completedTasks->where('priority', 'urgente')->count(),
            'alta' => $completedTasks->where('priority', 'alta')->count(),
            'media' => $completedTasks->where('priority', 'media')->count(),
            'baja' => $completedTasks->where('priority', 'baja')->count(),
        ];

        // Dynamic category distribution
        $categoriesData = \App\Models\Category::withCount(['tasks' => function($q) use ($start, $end, $isAdmin, $employeeId) {
            $q->where('status', 'completada')
              ->whereBetween('completed_at', [$start, $end]);
            if (!$isAdmin) {
                $q->where('assigned_to_id', $employeeId);
            }
        }])->get();

        $categoryDistribution = $categoriesData->mapWithKeys(function($cat) {
            return [$cat->name => $cat->tasks_count];
        })->toArray();

        $categoryDistribution['Otro'] = $completedTasks->whereNull('category_id')->count();

        // Group by department (of the requester)
        $departments = $completedTasks->groupBy(function($task) {
            return $task->requestedBy->department ?? 'General';
        })->map(function($tasks) {
            return $tasks->count();
        });

        return view('livewire.informes', [
            'stats' => $stats,
            'performance' => $performanceByEmployee,
            'priorities' => $priorityDistribution,
            'categories' => $categoryDistribution,
            'departments' => $departments,
            'rangeText' => $this->getRangeText(),
        ])->layout('layouts.app');
    }

    protected function getRangeText()
    {
        $start = \Carbon\Carbon::parse($this->startDate);
        $end = \Carbon\Carbon::parse($this->endDate);

        if ($this->timeRange === 'Dia') {
            return $start->format('d M Y');
        }

        if ($this->timeRange === 'Mes') {
            return $start->translatedFormat('F Y');
        }

        if ($this->timeRange === 'Año') {
            return $start->format('Y');
        }

        return $start->format('d M') . ' - ' . $end->format('d M Y');
    }
}

