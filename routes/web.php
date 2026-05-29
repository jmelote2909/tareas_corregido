<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

Route::get('dashboard', \App\Livewire\Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('nueva-solicitud', \App\Livewire\NuevaSolicitud::class)
    ->middleware(['auth', 'verified'])
    ->name('nueva-solicitud');

Route::get('mis-tareas', \App\Livewire\MisTareas::class)
    ->middleware(['auth', 'verified'])
    ->name('mis-tareas');

Route::get('tarea/{id}', \App\Livewire\DetalleTarea::class)
    ->middleware(['auth', 'verified'])
    ->name('detalle-tarea');

Route::get('equipo', \App\Livewire\Equipo::class)
    ->middleware(['auth', 'verified'])
    ->name('equipo');

Route::get('usuarios', \App\Livewire\Usuarios::class)
    ->middleware(['auth', 'verified'])
    ->name('usuarios');

Route::get('ajustes', \App\Livewire\Ajustes::class)
    ->middleware(['auth', 'verified'])
    ->name('ajustes');

Route::get('proyectos', \App\Livewire\Proyectos::class)
    ->middleware(['auth', 'verified'])
    ->name('proyectos');

Route::get('proyecto/{id}', \App\Livewire\DetalleProyecto::class)
    ->middleware(['auth', 'verified'])
    ->name('detalle-proyecto');

Route::get('informes', \App\Livewire\Informes::class)
    ->middleware(['auth', 'verified'])
    ->name('informes');

Route::get('archivo', \App\Livewire\Archivo::class)
    ->middleware(['auth', 'verified'])
    ->name('archivo');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
