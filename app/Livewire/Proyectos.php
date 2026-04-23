<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Employee;
use Livewire\Component;

class Proyectos extends Component
{
    public $showModal = false;
    
    // Form fields
    public $name;
    public $description;
    public $status = 'Planificación';
    public $priority = 'Media';
    public $startDate;
    public $endDate;
    public $budget;
    public $responsibleId;

    public function mount()
    {
        $this->startDate = now()->format('Y-m-d');
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->status = 'Planificación';
        $this->priority = 'Media';
        $this->startDate = now()->format('Y-m-d');
        $this->endDate = null;
        $this->budget = null;
        $this->responsibleId = null;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3',
            'status' => 'required',
            'priority' => 'required',
            'startDate' => 'required|date',
        ]);

        Project::create([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'budget' => $this->budget,
            // 'responsible_id' => $this->responsibleId, // Add to migration if needed
        ]);

        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $projects = Project::orderBy('created_at', 'desc')->get();
        $employees = Employee::where('is_active', true)->get();

        $stats = [
            'total' => $projects->count(),
            'planificacion' => $projects->where('status', 'Planificación')->count(),
            'en_progreso' => $projects->where('status', 'En Progreso')->count(),
            'completados' => $projects->where('status', 'Completada')->count(),
        ];

        return view('livewire.proyectos', [
            'projects' => $projects,
            'employees' => $employees,
            'stats' => $stats
        ])->layout('layouts.app');
    }
}
