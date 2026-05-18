<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\Employee;
use App\Models\User;
use App\Models\ProjectHistory;
use Livewire\Component;
use Livewire\WithFileUploads;

class Proyectos extends Component
{
    use WithFileUploads;

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

    // History fields
    public $newHistoryText;
    public $newHistoryImage;
    public $activeModalTab = 'history';

    public function mount()
    {
        $this->startDate = now()->format('Y-m-d');
    }

    public function openModal($id = null)
    {
        $this->resetForm();
        $this->activeModalTab = 'history';
        
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
        $this->newHistoryText = '';
        $this->newHistoryImage = null;
        $this->activeModalTab = 'history';
    }

    public function delete($id)
    {
        if (auth()->user()->role !== 'admin') return;
        Project::findOrFail($id)->delete();
    }

    public function addHistoryComment()
    {
        if (!$this->projectId) return;

        $this->validate([
            'newHistoryText' => 'required_without:newHistoryImage',
            'newHistoryImage' => 'nullable|file|max:10240',
        ]);

        $imagePath = null;
        if ($this->newHistoryImage) {
            $imagePath = $this->newHistoryImage->store('project_histories', 'public');
        }

        ProjectHistory::create([
            'project_id' => $this->projectId,
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'user_avatar' => auth()->user()->avatar ?? null,
            'text' => $this->newHistoryText,
            'image_path' => $imagePath,
            'type' => 'comment'
        ]);

        $this->newHistoryText = '';
        $this->newHistoryImage = null;
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
            $project = Project::with('users')->findOrFail($this->projectId);
            
            // Check changes for history
            $changes = [];
            
            if ($project->name != $this->name) {
                $changes[] = "El nombre cambió de '{$project->name}' a '{$this->name}'";
            }
            if ($project->description != $this->description) {
                $changes[] = "La descripción del proyecto ha sido actualizada.";
            }
            $oldStart = $project->start_date ? $project->start_date->format('Y-m-d') : null;
            if ($oldStart != $this->startDate) {
                $changes[] = "La fecha de inicio cambió de '" . ($oldStart ?: 'Ninguna') . "' a '{$this->startDate}'";
            }
            $oldEnd = $project->end_date ? $project->end_date->format('Y-m-d') : null;
            if ($oldEnd != $this->endDate) {
                $changes[] = "La fecha de fin cambió de '" . ($oldEnd ?: 'Ninguna') . "' a '" . ($this->endDate ?: 'Ninguna') . "'";
            }
            
            if ($project->budget != $this->budget) {
                $old = $project->budget ?: 0;
                $new = $this->budget ?: 0;
                $diff = $new - $old;
                $diffStr = $diff > 0 ? "aumentado en $" . number_format($diff, 2) : "reducido en $" . number_format(abs($diff), 2);
                $changes[] = "El presupuesto se ha $diffStr (de $" . number_format($old, 2) . " a $" . number_format($new, 2) . ")";
            }
            if ($project->status != $this->status) {
                $changes[] = "El estado cambió de '{$project->status}' a '{$this->status}'";
            }
            if ($project->priority != $this->priority) {
                $changes[] = "La prioridad cambió de '{$project->priority}' a '{$this->priority}'";
            }

            $oldUserIds = $project->users->pluck('id')->toArray();
            
            $project->update($data);
            $project->users()->sync($this->selectedUsers);

            $newUserIds = $this->selectedUsers;
            sort($oldUserIds);
            sort($newUserIds);

            if ($oldUserIds != $newUserIds) {
                $changes[] = "El equipo asignado al proyecto ha sido modificado.";
            }

            foreach ($changes as $change) {
                ProjectHistory::create([
                    'project_id' => $project->id,
                    'user_id' => auth()->id(),
                    'user_name' => auth()->user()->name,
                    'text' => $change,
                    'type' => 'system'
                ]);
            }

        } else {
            $project = Project::create($data);
            $project->users()->sync($this->selectedUsers);
            ProjectHistory::create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
                'text' => 'Proyecto creado.',
                'type' => 'system'
            ]);
        }

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

        $histories = [];
        if ($this->projectId) {
            $histories = ProjectHistory::where('project_id', $this->projectId)->orderBy('created_at', 'asc')->get();
        }

        return view('livewire.proyectos', [
            'projects' => $projects,
            'users' => $users,
            'stats' => $stats,
            'histories' => $histories
        ])->layout('layouts.app');
    }

}
