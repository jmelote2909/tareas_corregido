<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\Employee;
use Livewire\Component;

class MisTareas extends Component
{
    public $viewMode = 'list';
    public $viewTab = 'pendiente';
    public $selectedEmployeeId = null;
    public $showSelector = false;

    public function mount()
    {
        // Use relationship
        $employee = auth()->user()->employee;
        if ($employee) {
            $this->selectedEmployeeId = $employee->id;
            $this->showSelector = auth()->user()->role === 'admin';
        } else {
            $this->showSelector = auth()->user()->role === 'admin';
        }
    }

    public function selectEmployee($id)
    {
        $this->selectedEmployeeId = $id;
        $this->showSelector = false;
    }

    public function updateTaskStatus($taskId, $newStatus)
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->update(['status' => $newStatus]);
        }
    }

    public function render()
    {
        $employees = Employee::where('is_active', true)->get();
        $selectedEmployee = $this->selectedEmployeeId ? Employee::find($this->selectedEmployeeId) : null;
        
        $tasks = [];
        if ($selectedEmployee) {
            $tasks = Task::with(['requestedBy', 'attachments', 'comments'])
                ->where('assigned_to_id', $this->selectedEmployeeId)
                ->orderBy('priority', 'asc') // This should be a custom order really
                ->get();
        }

        $pendingTasks = collect($tasks)->where('status', 'pendiente');
        $inProgressTasks = collect($tasks)->where('status', 'en_proceso');
        $completedTasks = collect($tasks)->where('status', 'completada');

        return view('livewire.mis-tareas', [
            'employees' => $employees,
            'selectedEmployee' => $selectedEmployee,
            'pendingTasks' => $pendingTasks,
            'inProgressTasks' => $inProgressTasks,
            'completedTasks' => $completedTasks,
        ])->layout('layouts.app');
    }
}
