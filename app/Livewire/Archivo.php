<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\Employee;
use Livewire\Component;

class Archivo extends Component
{
    public $searchQuery = '';
    public $filterEmployee = 'all';
    public $filterCategory = 'all';
    public $filterYear = 'all';

    public function render()
    {
        $query = Task::where('is_archived', true);

        if (auth()->user()->role !== 'admin') {
            $employee = auth()->user()->employee;
            $query->where(function ($q) use ($employee) {
                $q->where('requested_by_id', auth()->id());
                if ($employee) {
                    $q->orWhere('assigned_to_id', $employee->id);
                }
            });
        }

        $archivedTasks = $query->orderBy('created_at', 'desc')->get();
        $employees = Employee::all();

        return view('livewire.archivo', [
            'archivedTasks' => $archivedTasks,
            'employees' => $employees
        ])->layout('layouts.app');
    }
}
