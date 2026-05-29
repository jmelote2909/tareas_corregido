<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarea Completada</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8f9fc;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            background-color: #f8f9fc;
            padding: 40px 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.05);
            border: 1px solid #eef2f6;
        }
        .header {
            background-color: #064e3b;
            padding: 32px;
            text-align: center;
        }
        .logo-badge {
            display: inline-block;
            background-color: #10b981;
            color: #ffffff;
            font-weight: bold;
            font-size: 20px;
            padding: 8px 16px;
            border-radius: 12px;
            margin-bottom: 12px;
        }
        .header-title {
            color: #ffffff;
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .body {
            padding: 40px 32px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #064e3b;
            margin-top: 0;
            margin-bottom: 12px;
        }
        .intro-text {
            font-size: 15px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .task-card {
            background-color: #f0fdf4;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #d1fae5;
            margin-bottom: 30px;
        }
        .task-title {
            font-size: 20px;
            font-weight: 700;
            color: #064e3b;
            margin-top: 0;
            margin-bottom: 16px;
        }
        .meta-list {
            list-style: none;
            padding: 0;
            margin: 0 0 20px 0;
        }
        .meta-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed #c6f6d5;
            font-size: 14px;
        }
        .meta-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .meta-label {
            color: #047857;
            font-weight: 500;
        }
        .meta-value {
            color: #064e3b;
            font-weight: 600;
            text-align: right;
        }
        .priority-badge {
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .priority-alta {
            background-color: #fee2e2;
            color: #ef4444;
        }
        .priority-media {
            background-color: #fef3c7;
            color: #f59e0b;
        }
        .priority-baja {
            background-color: #d1fae5;
            color: #10b981;
        }
        .task-desc {
            font-size: 14px;
            color: #374151;
            line-height: 1.6;
            background-color: #ffffff;
            border-radius: 12px;
            padding: 16px;
            border: 1px solid #e2e8f0;
            margin-top: 15px;
        }
        .desc-label {
            font-weight: 600;
            color: #047857;
            font-size: 12px;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .btn-container {
            text-align: center;
            margin-top: 10px;
        }
        .btn {
            display: inline-block;
            background-color: #10b981;
            color: #ffffff !important;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            padding: 16px 32px;
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.25);
            transition: all 0.2s ease;
        }
        .footer {
            text-align: center;
            padding: 30px;
            font-size: 12px;
            color: #94a3b8;
            border-top: 1px solid #eef2f6;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <div class="logo-badge">✓ Completada</div>
                <h1 class="header-title">Notificación de Tarea Completada</h1>
            </div>

            <!-- Body -->
            <div class="body">
                <p class="greeting">¡Hola Infraestructura!</p>
                <p class="intro-text">Te informamos que se ha completado con éxito una tarea en el sistema. A continuación se detallan todos los datos relacionados:</p>

                <!-- Task Card -->
                <div class="task-card">
                    <h2 class="task-title">{{ $task->title }}</h2>
                    
                    <div class="meta-list">
                        <div class="meta-item">
                            <span class="meta-label">Hecha por (Asignado)</span>
                            <span class="meta-value">{{ $task->assignedTo?->name ?? 'Sin asignar' }}</span>
                        </div>

                        <div class="meta-item">
                            <span class="meta-label">Solicitada por</span>
                            <span class="meta-value">{{ $task->requestedBy?->name ?? $task->requester_name ?? 'Sistema' }}</span>
                        </div>

                        <div class="meta-item">
                            <span class="meta-label">Fecha y Hora de Finalización</span>
                            <span class="meta-value">
                                @if($task->completed_at)
                                    {{ $task->completed_at->timezone('Europe/Madrid')->format('d/m/Y H:i') }}
                                @else
                                    {{ now()->timezone('Europe/Madrid')->format('d/m/Y H:i') }}
                                @endif
                            </span>
                        </div>

                        <div class="meta-item">
                            <span class="meta-label">Prioridad</span>
                            <span class="meta-value">
                                @if(strtolower($task->priority) == 'alta' || strtolower($task->priority) == 'high')
                                    <span class="priority-badge priority-alta">{{ $task->priority }}</span>
                                @elseif(strtolower($task->priority) == 'media' || strtolower($task->priority) == 'medium')
                                    <span class="priority-badge priority-media">{{ $task->priority }}</span>
                                @else
                                    <span class="priority-badge priority-baja">{{ $task->priority }}</span>
                                @endif
                            </span>
                        </div>
                        
                        <div class="meta-item">
                            <span class="meta-label">Fecha Límite original</span>
                            <span class="meta-value">
                                {{ $task->due_date ? $task->due_date->format('d/m/Y') : 'Sin fecha límite' }}
                                @if($task->due_time)
                                    a las {{ \Carbon\Carbon::parse($task->due_time)->format('H:i') }}
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="desc-label">Descripción de la tarea</div>
                    <div class="task-desc">
                        {!! nl2br(e($task->description)) !!}
                    </div>
                </div>

                <!-- Button -->
                <div class="btn-container">
                    <a href="{{ route('detalle-tarea', $task->id) }}" class="btn">Ver Detalles de la Tarea</a>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                Este es un correo automático generado por el Sistema de Gestión de Tareas.<br>
                Por favor, no respondas a este correo.
            </div>
        </div>
    </div>
</body>
</html>
