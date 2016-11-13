<?php

namespace App\Http\Controllers;

use App\Http\Repositories\TodoRepository;
use App\Http\Requests\StoreTodo;
use App\Todo;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;

class TodoController extends Controller // TODO: Encrypt
{
    protected $todos;

    public function __construct(TodoRepository $todos)
    {
        $this->todos = $todos;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->todos->forUser($request->user());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTodo $request)
    {
        $todo = new Todo($request->only('content', 'done'));

        $todo->id = Uuid::uuid1()->toString();

        return $todo->save();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTodo $request, Todo $todo)
    {
        $this->authorize('update', $todo);

        return $todo->update($request->only(['content', 'done']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        $this->authorize('update', $todo);

        return $todo->delete();
    }
}
