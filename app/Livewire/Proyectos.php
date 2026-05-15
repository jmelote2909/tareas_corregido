<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Employee;
use App\Models\User;
use Livewire\Component;


class Proyectos extends Component
{
    public $showModal = false;
    public $projectId;
    
    // Form fields
    public $name;
    public $description;
    public $status = 'Planificación';
    public $priority = 'Media';
    public $startDate;
    public $endDate;
    public $budget;
    public $selectedUsers = [];

    public function mount()
    {
        $this->startDate = now()->format('Y-m-d');
    }

    public function openModal($id = null)
    {
        $this->resetForm();
        
        if ($id) {
            $this->projectId = $id;
            $project = Project::with('users')->findOrFail($id);
            $this->name = $project->name;
            $this->description = $project->description;
            $this->status = $project->status;
            $this->priority = $project->priority;
            $this->startDate = $project->start_date?->format('Y-m-d') ?? now()->format('Y-m-d');
            $this->endDate = $project->end_date?->format('Y-m-d');
            $this->budget = $project->budget;
            $this->selectedUsers = $project->users->pluck('id')->toArray();
        }

        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->projectId = null;
        $this->name = '';
        $this->description = '';
        $this->status = 'Planificación';
        $this->priority = 'Media';
        $this->startDate = now()->format('Y-m-d');
        $this->endDate = null;
        $this->budget = null;
        $this->selectedUsers = [];
    }

    public function delete($id)
    {
        if (auth()->user()->role !== 'admin') return;
        Project::findOrFail($id)->delete();
    }

    public function save()
    {
        if (auth()->user()->role !== 'admin') return;
        $this->validate([
            'name' => 'required|min:3',
            'status' => 'required',
            'priority' => 'required',
            'startDate' => 'required|date',
        ]);

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'budget' => $this->budget,
        ];

        if ($this->projectId) {
            $project = Project::findOrFail($this->projectId);
            $project->update($data);
        } else {
            $project = Project::create($data);
        }

        $project->users()->sync($this->selectedUsers);

        $this->showModal = false;
        $this->resetForm();
    }


    public function render()
    {
        $query = Project::with('users');
        
        if (auth()->user()->role !== 'admin') {
            $query->whereHas('users', function($q) {
                $q->where('users.id', auth()->id());
            });
        }
        
        $projects = $query->orderBy('created_at', 'desc')->get();
        $users = User::where('is_active', true)->get();

        $stats = [
            'total' => $projects->count(),
            'planificacion' => $projects->where('status', 'Planificación')->count(),
            'en_progreso' => $projects->where('status', 'En Progreso')->count(),
            'completados' => $projects->where('status', 'Completada')->count(),
        ];

        return view('livewire.proyectos', [
            'projects' => $projects,
            'users' => $users,
            'stats' => $stats
        ])->layout('layouts.app');
    }

}
