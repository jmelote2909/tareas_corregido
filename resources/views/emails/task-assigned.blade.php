<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Tarea Asignada</title>
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
            box-shadow: 0 10px 30px rgba(67, 83, 255, 0.05);
            border: 1px solid #eef2f6;
        }
        .header {
            background-color: #1a2344;
            padding: 32px;
            text-align: center;
        }
        .logo-badge {
            display: inline-block;
            background-color: #4353ff;
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
            color: #1a2344;
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
            background-color: #f8f9fc;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #eef2f6;
            margin-bottom: 30px;
        }
        .task-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a2344;
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
            border-bottom: 1px dashed #e2e8f0;
            font-size: 14px;
        }
        .meta-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }
        .meta-label {
            color: #64748b;
            font-weight: 500;
        }
        .meta-value {
            color: #1a2344;
            font-weight: 600;
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
            color: #475569;
            line-height: 1.6;
            background-color: #ffffff;
            border-radius: 12px;
            padding: 16px;
            border: 1px solid #e2e8f0;
            margin-top: 15px;
        }
        .desc-label {
            font-weight: 600;
            color: #64748b;
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
            background-color: #3b49ff;
            color: #ffffff !important;
            text-decoration: none;
            font-weight: 700;
            font-size: 15px;
            padding: 16px 32px;
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(59, 73, 255, 0.25);
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
                <div class="logo-badge">✓ Tareas</div>
                <h1 class="header-title">Gestión de Infraestructura y TI</h1>
            </div>

            <!-- Body -->
            <div class="body">
                <p class="greeting">¡Hola!</p>
                <p class="intro-text">Se te ha asignado una nueva tarea en el sistema. A continuación se muestran los detalles principales para que puedas empezar a trabajar en ella:</p>

                <!-- Task Card -->
                <div class="task-card">
                    <h2 class="task-title">{{ $task->title }}</h2>
                    
                    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                        <tr style="border-bottom: 1px dashed #e2e8f0;">
                            <td style="padding: 10px 0; font-size: 14px; color: #64748b; font-weight: 500; text-align: left; border-top: none;">Prioridad</td>
                            <td style="padding: 10px 0; font-size: 14px; color: #1a2344; font-weight: 600; text-align: right; border-top: none;">
                                @if(strtolower($task->priority) == 'alta' || strtolower($task->priority) == 'high')
                                    <span class="priority-badge priority-alta">{{ $task->priority }}</span>
                                @elseif(strtolower($task->priority) == 'media' || strtolower($task->priority) == 'medium')
                                    <span class="priority-badge priority-media">{{ $task->priority }}</span>
                                @else
                                    <span class="priority-badge priority-baja">{{ $task->priority }}</span>
                                @endif
                            </td>
                        </tr>
                        
                        <tr style="border-bottom: 1px dashed #e2e8f0;">
                            <td style="padding: 10px 0; font-size: 14px; color: #64748b; font-weight: 500; text-align: left;">Fecha Límite</td>
                            <td style="padding: 10px 0; font-size: 14px; color: #1a2344; font-weight: 600; text-align: right;">
                                {{ $task->due_date ? $task->due_date->format('d/m/Y') : 'Sin fecha límite' }}
                                @if($task->due_time)
                                    a las {{ \Carbon\Carbon::parse($task->due_time)->format('H:i') }}
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td style="padding: 10px 0; font-size: 14px; color: #64748b; font-weight: 500; text-align: left;">Solicitado por</td>
                            <td style="padding: 10px 0; font-size: 14px; color: #1a2344; font-weight: 600; text-align: right;">{{ $task->requestedBy?->name ?? $task->requester_name ?? 'Sistema' }}</td>
                        </tr>
                    </table>

                    <div class="desc-label">Descripción de la tarea</div>
                    <div class="task-desc">
                        {!! nl2br(e($task->description)) !!}
                    </div>
                </div>

                <!-- Button -->
                <div class="btn-container">
                    <a href="{{ route('detalle-tarea', $task->id) }}" class="btn">Ver Detalles en la Plataforma</a>
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
