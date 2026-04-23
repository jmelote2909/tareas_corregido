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
        $archivedTasks = Task::where('is_archived', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $employees = Employee::all();

        return view('livewire.archivo', [
            'archivedTasks' => $archivedTasks,
            'employees' => $employees
        ])->layout('layouts.app');
    }
}
