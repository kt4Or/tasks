<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query();

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $sort = $request->get('sort', 'date');
        if ($sort === 'alpha') {
            $query->orderBy('name', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $tasks = $query->get();
        return view('tasks.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'due_date' => 'required|date',
                'status' => 'required|in:pending,in_progress,completed,cancelled', 
            ]);

            $task = Task::create($validated);
            
            return redirect()->route('tasks.index')->with('success', 'Задача успешно добавлена!');
        } catch (\Exception $e) {
            Log::error("[Ошибка Создания]: " . $e->getMessage());
            return redirect()->back()->with('error', 'Не удалось создать задачу: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, Task $task)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'due_date' => 'required|date',
                'status' => 'required|in:pending,in_progress,completed,cancelled', 
            ]);

            $task->update($validated);
            return redirect()->route('tasks.index')->with('success', 'Задача обновлена!');
        } catch (\Exception $e) {
            Log::error("[Ошибка Обновления]: " . $e->getMessage());
            return redirect()->back()->with('error', 'Ошибка при обновлении задачи.');
        }
    }

    public function updateStatus(Request $request, Task $task)
    {
        $task->update(['status' => $request->status]);
        return redirect()->route('tasks.index')->with('success', 'Статус изменен!');
    }

    public function destroy(Task $task)
    {
        try {
            $task->delete();
            return redirect()->route('tasks.index')->with('success', 'Задача удалена!');
        } catch (\Exception $e) {
            Log::error("[Ошибка Удаления]: " . $e->getMessage());
            return redirect()->back()->with('error', 'Не удалось удалить задачу.');
        }
    }
}