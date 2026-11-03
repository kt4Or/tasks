<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📋 Менеджер задач</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .main-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        .task-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .task-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        .task-card.status-pending { border-left-color: #ffc107; }
        .task-card.status-in_progress { border-left-color: #0dcaf0; }
        .task-card.status-completed { border-left-color: #198754; }
        .task-card.status-cancelled { border-left-color: #dc3545; }
        
        .task-name.completed {
            text-decoration: line-through;
            color: #6c757d;
        }
        .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
        }
        .hero-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 30px;
        }
        .stats-badge {
            background: rgba(255,255,255,0.2);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-container">
            
            <!-- Шапка -->
            <div class="hero-header text-center text-md-start">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div>
                        <h1 class="mb-2"><i class="bi bi-clipboard-check"></i> Менеджер задач</h1>
                        <p class="mb-0 opacity-75">Организуйте свой день эффективно</p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <span class="stats-badge">
                            <i class="bi bi-list-task"></i> Всего: {{ $tasks->count() }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-4">
                
                <!-- Уведомления -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Форма создания  -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Новая задача</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('tasks.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Название задачи <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" placeholder="Например: Сделать отчёт" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Срок выполнения <span class="text-danger">*</span></label>
                                    <input type="date" name="due_date" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Статус</label>
                                    <select name="status" class="form-select">
                                        <option value="pending">🟡 Новая</option>
                                        <option value="in_progress">🔵 В работе</option>
                                        <option value="completed">🟢 Выполнена</option>
                                        <option value="cancelled">🔴 Отменена</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Описание</label>
                                    <textarea name="description" class="form-control" rows="2" placeholder="Дополнительные детали..."></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-plus-lg"></i> Добавить задачу
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Панель фильтров -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <form action="{{ route('tasks.index') }}" method="GET">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-5">
                                    <label class="form-label"><i class="bi bi-funnel"></i> Фильтр по статусу</label>
                                    <select name="status" class="form-select" onchange="this.form.submit()">
                                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>📋 Все задачи</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>🟡 Новые</option>
                                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>🔵 В работе</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>🟢 Выполненные</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>🔴 Отмененные</option>
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label"><i class="bi bi-sort-down"></i> Сортировка</label>
                                    <select name="sort" class="form-select" onchange="this.form.submit()">
                                        <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>📅 Сначала новые</option>
                                        <option value="alpha" {{ request('sort') == 'alpha' ? 'selected' : '' }}>🔤 По алфавиту</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary w-100">
                                        <i class="bi bi-x-circle"></i> Сброс
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Список задач -->
                <div class="row g-3">
                    @forelse($tasks as $task)
                        <div class="col-12">
                            <div class="card task-card shadow-sm status-{{ $task->status }}">
                                <div class="card-body">
                                    <div class="d-flex align-items-start gap-3">
                                        
                                        <!-- Чекбокс статуса -->
                                        <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" class="mt-1">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $task->status === 'completed' ? 'pending' : 'completed' }}">
                                            <input type="checkbox" 
                                                   class="form-check-input" 
                                                   style="width: 22px; height: 22px; cursor: pointer;"
                                                   onchange="this.form.submit()" 
                                                   {{ $task->status === 'completed' ? 'checked' : '' }}>
                                        </form>

                                        <!-- Основ информация -->
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                                <h5 class="mb-1 task-name {{ $task->status === 'completed' ? 'completed' : '' }}">
                                                    {{ $task->name }}
                                                </h5>
                                                
                                                <!-- бадге статуса -->
                                                @if($task->status === 'pending')
                                                    <span class="badge bg-warning text-dark">🟡 Новая</span>
                                                @elseif($task->status === 'in_progress')
                                                    <span class="badge bg-info text-dark">🔵 В работе</span>
                                                @elseif($task->status === 'completed')
                                                    <span class="badge bg-success">🟢 Выполнена</span>
                                                @elseif($task->status === 'cancelled')
                                                    <span class="badge bg-danger">🔴 Отменена</span>
                                                @endif
                                            </div>

                                            @if($task->description)
                                                <p class="text-muted mb-2">{{ $task->description }}</p>
                                            @endif

                                            <div class="d-flex flex-wrap gap-3 small text-muted">
                                                <span><i class="bi bi-calendar-event"></i> 
                                                    Срок: {{ \Carbon\Carbon::parse($task->due_date)->format('d.m.Y') }}
                                                </span>
                                                <span><i class="bi bi-clock"></i> 
                                                    Создано: {{ \Carbon\Carbon::parse($task->created_at)->format('d.m.Y H:i') }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Кнопки действий -->
                                        <div class="d-flex gap-2">
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-primary"
                                                    onclick="toggleEditForm({{ $task->id }})"
                                                    title="Редактировать">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Удалить задачу?')"
                                                        title="Удалить">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Скрытая форма редактирования -->
                                    <div id="edit-form-{{ $task->id }}" class="mt-3 pt-3 border-top" style="display: none;">
                                        <form action="{{ route('tasks.update', $task) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <h6 class="mb-3"><i class="bi bi-pencil-square"></i> Редактирование</h6>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Название</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $task->name }}" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Срок</label>
                                                    <input type="date" name="due_date" class="form-control" value="{{ \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') }}" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Статус</label>
                                                    <select name="status" class="form-select">
                                                        <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>🟡 Новая</option>
                                                        <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>🔵 В работе</option>
                                                        <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>🟢 Выполнена</option>
                                                        <option value="cancelled" {{ $task->status === 'cancelled' ? 'selected' : '' }}>🔴 Отменена</option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">Описание</label>
                                                    <textarea name="description" class="form-control" rows="2">{{ $task->description }}</textarea>
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="bi bi-check-lg"></i> Сохранить
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-secondary btn-sm"
                                                            onclick="toggleEditForm({{ $task->id }})">
                                                        <i class="bi bi-x-lg"></i> Отмена
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                                <h4 class="text-muted mt-3">Задач пока нет</h4>
                                <p class="text-muted">Создайте первую задачу выше ☝️</p>
                            </div>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleEditForm(id) {
            const form = document.getElementById(`edit-form-${id}`);
            if (form) {
                form.style.display = form.style.display === 'none' ? 'block' : 'none';
            }
        }
    </script>
</body>
</html>