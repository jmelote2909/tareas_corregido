<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\Employee;
use Livewire\Component;

class Dashboard extends Component
{
    public $searchQuery = '';
    public $filterEmployee = 'all';
    public $activeTab = 'list';
    public $calendarMonth;
    public $calendarYear;

    public function mount()
    {
        $this->calendarMonth = now()->month;
        $this->calendarYear  = now()->year;
    }

    public function prevMonth()
    {
        $date = \Carbon\Carbon::createFromDate($this->calendarYear, $this->calendarMonth, 1)->subMonth();
        $this->calendarMonth = $date->month;
        $this->calendarYear  = $date->year;
    }

    public function goToToday()
    {
        $this->calendarMonth = now()->month;
        $this->calendarYear  = now()->year;
    }

    public function nextMonth()
    {
        $date = \Carbon\Carbon::createFromDate($this->calendarYear, $this->calendarMonth, 1)->addMonth();
        $this->calendarMonth = $date->month;
        $this->calendarYear  = $date->year;
    }

    public function render()
    {
        $isAdmin = auth()->user()->role === 'admin';

        $tasksQuery = Task::with(['assignedTo', 'requestedBy', 'project'])
            ->where('is_archived', false);

        if (!$isAdmin) {
            $employee = auth()->user()->employee;
            $tasksQuery->where(function ($query) use ($employee) {
                $query->where('requested_by_id', auth()->id());
                if ($employee) {
                    $query->orWhere('assigned_to_id', $employee->id);
                }
            });
        } elseif ($this->filterEmployee !== 'all') {
            $tasksQuery->where('assigned_to_id', $this->filterEmployee);
        }

        if ($this->searchQuery) {
            $tasksQuery->where(function ($query) {
                $query->where('title', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('description', 'like', '%' . $this->searchQuery . '%')
                    ->orWhere('requester_name', 'like', '%' . $this->searchQuery . '%');
            });
        }

        $baseQuery = clone $tasksQuery;

          $totalTasks = (clone $baseQuery)->count();
          $completedCount = (clone $baseQuery)->where('status', 'completada')->count();

          $stats = [
              'total' => $totalTasks,
              'pendiente' => (clone $baseQuery)->where('status', 'pendiente')->count(),
              'en_proceso' => (clone $baseQuery)->where('status', 'en_proceso')->count(),
              'completada' => $completedCount,
              'urgente' => (clone $baseQuery)->where('priority', 'alta')->count(),
              'eficiencia' => $totalTasks > 0 ? round(($completedCount / $totalTasks) * 100) : 0,
                   ];

              $allTasks = (clone $baseQuery)
               ->orderByRaw("CASE WHEN status = 'completada' THEN 1 ELSE 0 END")
               ->orderBy('created_at', 'desc')
               ->limit(50)
               ->get();

              $unassignedTasks = $allTasks->whereNull('assigned_to_id');
              $assignedTasks = $allTasks->whereNotNull('assigned_to_id');

        $employees = Employee::where('is_active', true)->orderBy('name')->get();

        // Group tasks by due date for calendar
        $tasksByDate = $allTasks
            ->filter(fn($t) => $t->due_date)
            ->groupBy(fn($t) => $t->due_date->format('Y-m-d'));

        // Calendar calculations
        $date = \Carbon\Carbon::createFromDate($this->calendarYear, $this->calendarMonth, 1);
        $monthStart = $date->copy()->startOfMonth();
        $monthEnd = $date->copy()->endOfMonth();
        
        $daysInMonth = [];
        $currentDay = $monthStart->copy();
        while ($currentDay <= $monthEnd) {
            $daysInMonth[] = $currentDay->copy();
            $currentDay->addDay();
        }
        
        $firstDayOfWeek = $monthStart->dayOfWeek; // 0 = Sunday, 1 = Monday
        $adjustedFirstDay = $firstDayOfWeek === 0 ? 6 : $firstDayOfWeek - 1;

        return view('livewire.dashboard', [
            'allTasks'         => $allTasks,
            'unassignedTasks'  => $unassignedTasks,
            'assignedTasks'    => $assignedTasks,
            'employees'        => $employees,
            'stats'            => $stats,
            'isAdmin'          => $isAdmin,
            'tasksByDate'      => $tasksByDate,
            'daysInMonth'      => $daysInMonth,
            'adjustedFirstDay' => $adjustedFirstDay,
            'currentDate'      => $date,
        ])->layout('layouts.app');
    }
}
