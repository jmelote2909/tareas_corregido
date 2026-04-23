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
        $this->startDate = now()->startOfWeek();
        $this->endDate = now()->endOfWeek();
    }

    public function setRange($range)
    {
        $this->timeRange = $range;
        // Logic to update dates based on range would go here
    }

    public function render()
    {
        $tasks = Task::whereBetween('created_at', [$this->startDate, $this->endDate])->get();
        $completedTasks = $tasks->where('status', 'completada');

        $stats = [
            'completados' => $completedTasks->count(),
            'creados' => $tasks->count(),
            'tiempo_medio' => 0, // Mock for now
            'urgentes_resueltos' => $completedTasks->where('priority', 'alta')->count(),
        ];

        return view('livewire.informes', [
            'stats' => $stats
        ])->layout('layouts.app');
    }
}
