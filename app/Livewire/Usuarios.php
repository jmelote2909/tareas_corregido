<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Team;
use App\Models\Employee;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class Usuarios extends Component
{
    public $showModal = false;
    public $searchQuery = '';

    public $editingUser = null;
    public $editMode = false;

    // Form fields
    public $username;
    public $password;
    public $name;
    public $email;
    public $department = '';
    public $team_id = null;
    public $role = 'employee';

    // New team logic
    public $newTeamName = '';
    public $showNewTeamInput = false;

    public function mount()
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('dashboard');
        }
    }

    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->username = '';
        $this->password = '';
        $this->name = '';
        $this->email = '';
        $this->department = '';
        $this->team_id = null;
        $this->role = 'employee';
        $this->editingUser = null;
        $this->newTeamName = '';
        $this->showNewTeamInput = false;
    }

    public function edit(User $user)
    {
        $this->resetForm();
        $this->editingUser = $user;
        $this->username = $user->username;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->department = $user->department;
        $this->team_id = $user->team_id;
        $this->role = $user->role;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function toggleNewTeamInput()
    {
        $this->showNewTeamInput = !$this->showNewTeamInput;
        if (!$this->showNewTeamInput) {
            $this->newTeamName = '';
        }
    }

    public function createTeam()
    {
        $this->validate([
            'newTeamName' => 'required|min:3|unique:teams,name'
        ]);

        $team = Team::create([
            'name' => $this->newTeamName,
            'color' => '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT),
        ]);

        $this->team_id = $team->id;
        $this->department = $team->name;
        $this->newTeamName = '';
        $this->showNewTeamInput = false;
    }

    public function delete(User $user)
    {
        if ($user->username === 'admin') {
            return;
        }
        
        // Delete associated employee if exists
        Employee::where('user_id', $user->id)->delete();
        
        $user->delete();
    }

    public function save()
    {
        $rules = [
            'username' => 'required|unique:users,username' . ($this->editMode ? ',' . $this->editingUser->id : ''),
            'password' => $this->editMode ? 'nullable|min:6' : 'required|min:6',
            'name' => 'required',
            'email' => 'required|email|unique:users,email' . ($this->editMode ? ',' . $this->editingUser->id : ''),
            'team_id' => 'required',
            'role' => 'required',
        ];

        $this->validate($rules);

        if ($this->editMode) {
            $team = Team::find($this->team_id);
            $data = [
                'username' => $this->username,
                'name' => $this->name,
                'email' => $this->email,
                'department' => $team ? $team->name : $this->department,
                'team_id' => $this->team_id,
                'role' => $this->role,
            ];
            if ($this->password) {
                $data['password'] = $this->password;
            }
            $this->editingUser->update($data);
            $user = $this->editingUser;
        } else {
            $team = Team::find($this->team_id);
            $user = User::create([
                'username' => $this->username,
                'password' => $this->password,
                'name' => $this->name,
                'email' => $this->email,
                'department' => $team ? $team->name : $this->department,
                'team_id' => $this->team_id,
                'role' => $this->role,
            ]);
        }

        // Sync with Employee
        Employee::updateOrCreate(
            ['user_id' => $user->id],
            [
                'team_id' => $user->team_id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role === 'admin' ? 'Administrador' : ($user->role === 'requester' ? 'Solicitante' : 'Empleado'),
                'is_active' => $user->is_active ?? true,
                'color' => '#6366f1', // Default color
            ]
        );

        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $usersQuery = User::query();

        if ($this->searchQuery) {
            $usersQuery->where(function($q) {
                $q->where('name', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('username', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('email', 'like', '%' . $this->searchQuery . '%');
            });
        }

        $users = $usersQuery->get();

        $stats = [
            'total' => $users->count(),
            'activos' => $users->where('is_active', true)->count(),
            'admins' => $users->where('role', 'admin')->count(),
            'empleados' => $users->where('role', 'employee')->count(),
            'solicitantes' => $users->where('role', 'requester')->count(),
        ];

        return view('livewire.usuarios', [
            'users' => $users,
            'stats' => $stats,
            'teams' => Team::all()
        ])->layout('layouts.app');
    }
}
