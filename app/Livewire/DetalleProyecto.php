<?php

namespace App\Livewire;

use App\Models\Project;
use App\Models\ProjectHistory;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class DetalleProyecto extends Component
{
    use WithFileUploads;

    public $projectId;
    public $newComment = '';
    public $isEditing = false;
    
    public $editName;
    public $editDescription;
    public $editStartDate;
    public $editEndDate;
    public $editBudget;
    public $editStatus;
    public $editPriority;
    public $editSelectedUsers = [];

    public $newPhotos = [];
    public $newAudio = null;
    public $newDocuments = [];

    public function mount($id)
    {
        $this->projectId = $id;
        $project = Project::with('users')->findOrFail($id);
        
        $this->editName = $project->name;
        $this->editDescription = $project->description;
        $this->editStartDate = $project->start_date ? $project->start_date->format('Y-m-d') : '';
        $this->editEndDate = $project->end_date ? $project->end_date->format('Y-m-d') : '';
        $this->editBudget = $project->budget;
        $this->editStatus = $project->status;
        $this->editPriority = $project->priority;
        $this->editSelectedUsers = $project->users->pluck('id')->toArray();
    }

    public function startEditing()
    {
        $project = Project::with('users')->findOrFail($this->projectId);
        $this->editName = $project->name;
        $this->editDescription = $project->description;
        $this->editStartDate = $project->start_date ? $project->start_date->format('Y-m-d') : '';
        $this->editEndDate = $project->end_date ? $project->end_date->format('Y-m-d') : '';
        $this->editBudget = $project->budget;
        $this->editStatus = $project->status;
        $this->editPriority = $project->priority;
        $this->editSelectedUsers = $project->users->pluck('id')->toArray();
        $this->isEditing = true;
    }

    public function cancelEditing()
    {
        $this->isEditing = false;
    }

    public function addComment()
    {
        $this->validate(['newComment' => 'required|min:1']);
        
        ProjectHistory::create([
            'project_id' => $this->projectId,
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'user_avatar' => auth()->user()->avatar ?? null,
            'text' => $this->newComment,
            'type' => 'comment',
        ]);

        $this->newComment = '';
    }

    public function saveEdit()
    {
        if (auth()->user()->role !== 'admin') return;

        $this->validate([
            'editName' => 'required|min:3',
            'editStartDate' => 'required|date',
            'editStatus' => 'required',
            'editPriority' => 'required',
        ]);

        $project = Project::with('users')->findOrFail($this->projectId);
        
        $data = [
            'name' => $this->editName,
            'description' => $this->editDescription,
            'status' => $this->editStatus,
            'priority' => $this->editPriority,
            'start_date' => $this->editStartDate,
            'end_date' => $this->editEndDate ?: null,
            'budget' => $this->editBudget,
        ];

        // Check changes for history
        $changes = [];
        
        if ($project->name != $this->editName) {
            $changes[] = "El nombre cambió de '{$project->name}' a '{$this->editName}'";
        }
        if ($project->description != $this->editDescription) {
            $changes[] = "La descripción del proyecto ha sido actualizada.";
        }
        $oldStart = $project->start_date ? $project->start_date->format('Y-m-d') : null;
        if ($oldStart != $this->editStartDate) {
            $changes[] = "La fecha de inicio cambió de '" . ($oldStart ?: 'Ninguna') . "' a '{$this->editStartDate}'";
        }
        $oldEnd = $project->end_date ? $project->end_date->format('Y-m-d') : null;
        if ($oldEnd != $this->editEndDate) {
            $changes[] = "La fecha de fin cambió de '" . ($oldEnd ?: 'Ninguna') . "' a '" . ($this->editEndDate ?: 'Ninguna') . "'";
        }
        
        if ($project->budget != $this->editBudget) {
            $old = $project->budget ?: 0;
            $new = $this->editBudget ?: 0;
            $diff = $new - $old;
            $diffStr = $diff > 0 ? "aumentado en $" . number_format($diff, 2) : "reducido en $" . number_format(abs($diff), 2);
            $changes[] = "El presupuesto se ha $diffStr (de $" . number_format($old, 2) . " a $" . number_format($new, 2) . ")";
        }
        if ($project->status != $this->editStatus) {
            $changes[] = "El estado cambió de '{$project->status}' a '{$this->editStatus}'";
        }
        if ($project->priority != $this->editPriority) {
            $changes[] = "La prioridad cambió de '{$project->priority}' a '{$this->editPriority}'";
        }

        $oldUserIds = $project->users->pluck('id')->toArray();
        
        $project->update($data);
        $project->users()->sync($this->editSelectedUsers);

        $newUserIds = $this->editSelectedUsers;
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

        $this->isEditing = false;
        session()->flash('success_message', 'Proyecto guardado con éxito.');
    }

    public function deleteProject()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Acción no autorizada.');
        }

        $project = Project::findOrFail($this->projectId);
        
        // Safety cascade deletion
        ProjectHistory::where('project_id', $project->id)->delete();
        $project->users()->detach();
        $project->delete();

        return redirect()->route('proyectos');
    }

    public function addAttachments()
    {
        $project = Project::findOrFail($this->projectId);

        // Handle photos
        foreach ($this->newPhotos as $photo) {
            $path = $photo->store('project_histories', 'public');
            ProjectHistory::create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
                'user_avatar' => auth()->user()->avatar ?? null,
                'text' => 'Subió una foto',
                'type' => 'attachment',
                'image_path' => $path,
            ]);
        }

        // Handle audio
        if ($this->newAudio) {
            $path = $this->newAudio->store('project_histories', 'public');
            ProjectHistory::create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
                'user_avatar' => auth()->user()->avatar ?? null,
                'text' => 'Subió un audio',
                'type' => 'attachment',
                'image_path' => $path,
            ]);
        }

        // Handle documents
        foreach ($this->newDocuments as $doc) {
            $path = $doc->store('project_histories', 'public');
            ProjectHistory::create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
                'user_avatar' => auth()->user()->avatar ?? null,
                'text' => 'Subió un documento: ' . $doc->getClientOriginalName(),
                'type' => 'attachment',
                'image_path' => $path,
            ]);
        }

        // Reset
        $this->newPhotos = [];
        $this->newAudio = null;
        $this->newDocuments = [];

        session()->flash('success_attachments', 'Archivos adjuntados con éxito.');
    }

    public function deleteAttachment($historyId)
    {
        $history = ProjectHistory::findOrFail($historyId);
        
        if ($history->image_path) {
            $relativePath = $history->image_path;
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($relativePath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($relativePath);
            }
        }

        $history->delete();
        session()->flash('success_attachments', 'Archivo eliminado con éxito.');
    }

    public function render()
    {
        $project = Project::with(['users'])->findOrFail($this->projectId);
        $histories = ProjectHistory::where('project_id', $this->projectId)->orderBy('created_at', 'asc')->get();
        $users = User::where('is_active', true)->get();
        $isAdmin = auth()->user()->role === 'admin';

        // Separate histories into comments/system vs attachments based on image_path and type
        $commentsAndSystem = $histories->filter(function ($h) {
            return !$h->image_path;
        });

        $attachments = $histories->filter(function ($h) {
            return $h->image_path;
        });

        return view('livewire.detalle-proyecto', [
            'project' => $project,
            'histories' => $commentsAndSystem,
            'attachments' => $attachments,
            'users' => $users,
            'isAdmin' => $isAdmin,
        ])->layout('layouts.app');
    }
}
