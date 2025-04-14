<?php

namespace App\Http\Controllers;

use App\Models\SubTask;
use Illuminate\Http\Request;

class SubTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subTasks = SubTask::all();
        return view('subtask', compact('subTasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $task)
    {
        $request->validate([
            'title' => 'required',
            'task_id' => 'required|exists:tasks,id',
            'status' => 'required|in:on_progress,done',
            'priority' => 'required|in:rendah,sedang,tinggi',
            'deadline' => 'required|date',
        ]);
    
        SubTask::create([
            'title' => $request->title,
            'task_id' => $request->task_id,
            'status' => $request->status,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
        ]);
    
        return redirect()->back()->with('success', 'Subtask berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubTask $subTask)
    {
        // Show the details of a specific subtask
        return view('subtask.show', compact('subTasks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubTask $subTask)
    {
        // Show the form to edit a specific subtask
        return view('subtask.edit', compact('subTasks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi data input
        $request->validate([
            'title' => 'required|string|max:255',
            'deadline' => 'nullable|date',
            'priority' => 'nullable|string|max:20',
        ]);
    
        // Cari subtugas berdasarkan ID
        $subtask = Subtask::findOrFail($id);
    
        // Update data subtugas
        $subtask->title = $request->title;
        $subtask->deadline = $request->deadline;
        $subtask->priority = $request->priority;
        $subtask->save();
    
        // Redirect balik ke halaman tugas dengan pesan sukses
        return redirect()->route('tasks.show', $subtask->task_id)->with('success', 'Subtugas berhasil diperbarui!');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subtask $subtask)
    {
        // Pastikan hanya menghapus subtask yang sesuai
        $subtask->delete();
    
        return redirect()->route('tasks.show', $subtask->task_id)->with('success', 'Subtask berhasil dihapus!');
    }
    

    public function toggleStatus(SubTask $subtask)
    {
        $subtask->status = $subtask->status === 'done' ? 'on_progress' : 'done';
        $subtask->save();

        return redirect()->back()->with('success', 'Status subtask berhasil diperbarui');
    }

}
