<?php

namespace App\Livewire;

use App\Models\User;
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
    public $department = 'Infraestructura y TI';
    public $role = 'employee';

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
        $this->department = 'Infraestructura y TI';
        $this->role = 'employee';
        $this->editingUser = null;
    }

    public function edit(User $user)
    {
        $this->resetForm();
        $this->editingUser = $user;
        $this->username = $user->username;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->department = $user->department ?? 'Infraestructura y TI';
        $this->role = $user->role;
        $this->editMode = true;
        $this->showModal = true;
    }

    public function delete(User $user)
    {
        if ($user->username === 'admin') {
            return;
        }
        $user->delete();
    }

    public function save()
    {
        $rules = [
            'username' => 'required|unique:users,username' . ($this->editMode ? ',' . $this->editingUser->id : ''),
            'password' => $this->editMode ? 'nullable|min:6' : 'required|min:6',
            'name' => 'required',
            'email' => 'required|email|unique:users,email' . ($this->editMode ? ',' . $this->editingUser->id : ''),
            'department' => 'required',
            'role' => 'required',
        ];

        $this->validate($rules);

        if ($this->editMode) {
            $data = [
                'username' => $this->username,
                'name' => $this->name,
                'email' => $this->email,
                'department' => $this->department,
                'role' => $this->role,
            ];
            if ($this->password) {
                $data['password'] = $this->password;
            }
            $this->editingUser->update($data);
        } else {
            User::create([
                'username' => $this->username,
                'password' => $this->password,
                'name' => $this->name,
                'email' => $this->email,
                'department' => $this->department,
                'role' => $this->role,
            ]);
        }

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
            'stats' => $stats
        ])->layout('layouts.app');
    }
}
