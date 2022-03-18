<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $tasks = Task::where('user_id', Auth::user()->id)->orderBy('id', 'DESC');

        if (!empty($request->client_id)) {
            $tasks = $tasks->where('client_id', $request->client_id);
        }
        if (!empty($request->status)) {
            $tasks = $tasks->where('status', $request->status);
        }
        if (!empty($request->price)) {
            $tasks = $tasks->where('price', '<=', $request->price);
        }
        if (!empty($request->fromDate)) {
            $tasks = $tasks->whereDate('created_at', '>=', $request->fromDate);
        }
        if (!empty($request->toDate)) {
            $tasks = $tasks->whereDate('created_at', '<=', $request->endDate);
        }

        $tasks = $tasks->paginate(10)->withQueryString();

        return view('task.index')->with([
            'clients'   => Client::where('user_id', Auth::user()->id)->get(),
            'tasks'     => $tasks
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('task.create')->with([
            'clients' => Client::where('user_id', Auth::user()->id)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->taskValidation($request);

        Task::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => $request->price,
            'description' => $request->description,
            'client_id' => $request->client_id,
            'user_id' => Auth::user()->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'priority' => $request->priority
        ]);

        return redirect()->route('task.index')->with('success', 'Task Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $task = Task::where('slug', $slug)->get()->first();
        return view('task.show')->with('task', $task);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        return view('task.edit')->with([
            'task' => $task,
            'clients' => Client::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $this->taskValidation($request);
        $task->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price' => $request->price,
            'description' => $request->description,
            'client_id' => $request->client_id,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('task.index')->with('success', 'Task Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('task.index')->with('success', 'Task Deleted!');
    }


    public function taskValidation(Request $request)
    {
        return $request->validate([
            'name'  => ['required', 'max:255', 'string'],
            'price' => ['required', 'integer'],
            'client_id' => ['required', 'max:255', 'not_in:none'],
            'start_date' => ['required', 'max:255'],
            'end_date' => ['required', 'max:255'],
            'priority'      => ['required', 'in:normal,medium,high']
        ]);
    }

    public function markAsComplete(Task $task)
    {
        $task->update([
            'status' => 'complete'
        ]);
        return redirect()->back()->with('success', 'Task mark as completed');
    }
}
