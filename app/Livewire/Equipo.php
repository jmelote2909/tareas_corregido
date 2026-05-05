<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\User;
use App\Models\Task;
use App\Models\Team;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Equipo extends Component
{
    use WithFileUploads;

    public $view = 'teams'; // 'teams' or 'members'
    public $selectedTeamId = null;
    public $showTeamModal = false;
    public $showMemberModal = false;
    public $userSearch = '';
    
    // Team fields
    public $teamId;
    public $teamName;
    public $teamDescription;
    public $teamColor = '#6366f1';

    // Member fields (formerly employee)
    public $editingEmployeeId = null;
    public $name;
    public $email;
    public $username;
    public $password;
    public $role = 'employee';
    public $position;
    public $color = '#6366f1';
    public $avatar;
    public $isActive = true;

    public function mount()
    {
        if (auth()->user()->role !== 'admin') {
            $this->view = 'members';
            $this->selectedTeamId = auth()->user()->team_id;
        }
    }

    // Team Methods
    public function openTeamModal($id = null)
    {
        $this->resetTeamForm();
        if ($id) {
            $team = Team::findOrFail($id);
            $this->teamId = $team->id;
            $this->teamName = $team->name;
            $this->teamDescription = $team->description;
            $this->teamColor = $team->color;
        }
        $this->showTeamModal = true;
    }

    public function resetTeamForm()
    {
        $this->teamId = null;
        $this->teamName = '';
        $this->teamDescription = '';
        $this->teamColor = '#6366f1';
    }

    public function saveTeam()
    {
        $this->validate([
            'teamName' => 'required',
            'teamColor' => 'required',
        ]);

        $data = [
            'name' => $this->teamName,
            'description' => $this->teamDescription,
            'color' => $this->teamColor,
        ];

        if ($this->teamId) {
            Team::find($this->teamId)->update($data);
        } else {
            Team::create($data);
        }

        $this->showTeamModal = false;
        $this->resetTeamForm();
    }

    public function deleteTeam($id)
    {
        Team::findOrFail($id)->delete();
    }

    public function selectTeam($id)
    {
        $this->selectedTeamId = $id;
        $this->view = 'members';
    }

    public function backToTeams()
    {
        $this->selectedTeamId = null;
        $this->view = 'teams';
    }

    // Member Methods
    public function openMemberModal($id = null)
    {
        $this->resetMemberForm();
        $this->userSearch = '';
        if ($id) {
            $this->editingEmployeeId = $id;
            $employee = Employee::findOrFail($id);
            $this->name = $employee->name;
            $this->email = $employee->email;
            $this->role = $employee->role === 'Administrador' ? 'admin' : 'employee';
            $this->color = $employee->color;
            $this->isActive = $employee->is_active;
            
            if ($employee->user_id) {
                $user = User::find($employee->user_id);
                if ($user) {
                    $this->username = $user->username;
                    $this->position = $user->position;
                }
            }
        }
        $this->showMemberModal = true;
    }

    public function resetMemberForm()
    {
        $this->editingEmployeeId = null;
        $this->name = '';
        $this->email = '';
        $this->username = '';
        $this->password = '';
        $this->role = 'employee';
        $this->position = '';
        $this->color = '#6366f1';
        $this->avatar = null;
        $this->isActive = true;
    }

    public function saveMember()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        if (!$this->editingEmployeeId && $this->username) {
            $this->validate(['password' => 'required|min:6']);
        }

        // 1. Create or Update User
        $userId = $this->editingEmployeeId ? Employee::find($this->editingEmployeeId)->user_id : null;
        
        if ($this->username) {
            $userData = [
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username,
                'role' => $this->role,
                'team_id' => $this->selectedTeamId,
                'position' => $this->position,
                'is_active' => $this->isActive,
            ];

            if ($this->password) {
                $userData['password'] = $this->password;
            }

            if ($userId) {
                User::find($userId)?->update($userData);
            } else {
                $user = User::create($userData);
                $userId = $user->id;
            }
        }

        // 2. Create or Update Employee
        $employeeData = [
            'user_id' => $userId,
            'team_id' => $this->selectedTeamId,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role === 'admin' ? 'Administrador' : ($this->role === 'requester' ? 'Solicitante' : 'Empleado'),
            'color' => $this->color,
            'is_active' => $this->isActive,
        ];

        if ($this->avatar) {
            $employeeData['avatar'] = '/storage/' . $this->avatar->store('avatars', 'public');
        }

        if ($this->editingEmployeeId) {
            Employee::find($this->editingEmployeeId)->update($employeeData);
        } else {
            Employee::create($employeeData);
        }

        $this->showMemberModal = false;
        $this->resetMemberForm();
    }

    public function addToTeam($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['team_id' => $this->selectedTeamId]);

        Employee::updateOrCreate(
            ['user_id' => $user->id],
            [
                'team_id' => $this->selectedTeamId,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role === 'admin' ? 'Administrador' : ($user->role === 'requester' ? 'Solicitante' : 'Empleado'),
                'is_active' => $user->is_active ?? true,
                'color' => '#6366f1',
            ]
        );

        $this->showMemberModal = false;
    }

    public function removeFromTeam($id)
    {
        \Illuminate\Support\Facades\Log::info('removeFromTeam triggered for ID: ' . $id);
        $employee = Employee::findOrFail($id);
        
        // Find or create the "Sin Asignar" team
        $unassignedTeam = Team::firstOrCreate(
            ['name' => 'Sin Asignar'],
            [
                'description' => 'Miembros que no tienen un equipo específico asignado',
                'color' => '#94a3b8' // Slate color
            ]
        );

        if ($employee->user_id) {
            User::find($employee->user_id)?->update(['team_id' => $unassignedTeam->id]);
        }
        $employee->update(['team_id' => $unassignedTeam->id]);

        // If the current team is the one being viewed, it will disappear from the list
    }

    public function deleteMember($id)
    {
        $employee = Employee::findOrFail($id);
        
        // Don't allow deleting yourself
        if ($employee->user_id === auth()->id()) {
            return;
        }

        if ($employee->user_id) {
            User::find($employee->user_id)?->delete();
        }
        $employee->delete();
    }

    public function render()
    {
        $teams = Team::all();
        $employees = Employee::all(); // Load all for the teams grid avatars
        $selectedTeam = null;
        $availableUsers = collect();

        if (auth()->user()->role !== 'admin') {
            $this->view = 'members';
            $this->selectedTeamId = auth()->user()->team_id;
        }

        if ($this->view === 'members' && $this->selectedTeamId) {
            $employees = Employee::where('team_id', $this->selectedTeamId)->get();
            $selectedTeam = Team::find($this->selectedTeamId);
            
            if ($this->showMemberModal && !$this->editingEmployeeId) {
                $availableUsers = User::where(function($q) {
                    $q->where('name', 'like', '%' . $this->userSearch . '%')
                      ->orWhere('email', 'like', '%' . $this->userSearch . '%');
                })
                ->where(function($q) {
                    $q->where('team_id', '!=', $this->selectedTeamId)
                      ->orWhereNull('team_id');
                })
                ->limit(10)
                ->get();
            }
        }

        return view('livewire.equipo', [
            'teams' => $teams,
            'employees' => $employees,
            'selectedTeam' => $selectedTeam,
            'availableUsers' => $availableUsers,
            'tasks' => Task::all(),
        ])->layout('layouts.app');
    }
}
