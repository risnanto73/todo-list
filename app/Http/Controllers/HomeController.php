<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Todo;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $todo = Todo::where('user_id', auth()->user()->id)
            ->select('id', 'title', 'description', 'status', 'deadline')
            ->latest()->get();

        return view('home', compact(
            'todo'
        ));
    }

    public function storeTodo(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
        ]);

        try {
            $data = $request->all();
            $data['user_id'] = auth()->user()->id;
            $todo = Todo::create($data);

            return back()->with('success', 'Todo created successfully');

        } catch (Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'Failed to create todo');
        }
    }

    public function updateTodo(Request $request, $id)
    {
        try {
            $todo = Todo::find($id);
            $todo->update($request->all());
        } catch (Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'Failed to update todo title');
        }
    }

    public function updateTodoStatus(Request $request, $id)
    {
        try {
            $todo = Todo::find($id);
            $todo->status = $request->status;
            $todo->save();
        } catch (Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'Failed to update todo status');
        }
    }

    public function deleteTodo($id)
    {
        try {
            $todo = Todo::find($id);
            $todo->delete();

            return back()->with('success', 'Todo deleted successfully');

        } catch (Exception $e) {
            dd($e->getMessage());
            return back()->with('error', 'Failed to delete todo');
        }
    }
}
