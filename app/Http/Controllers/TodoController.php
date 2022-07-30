<?php


namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TodoController extends Controller
{
    /** This is the method returns all todo items for a user
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $todos = $request->user->todos;
        return response()->json($todos, 200);
    }

    /** This function is used to create a new todo-item
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function create(Request $request): JsonResponse
    {

        $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255'
        ]);

        $todo = new Todo;

        $todo->title = $request->title;
        $todo->description = $request->description;
        $todo->completed = $request->completed ?? false;
        $todo->user_id = $request->user->id;
        $todo->save();

        return response()->json([
            'message' => 'Todo created successfully'
        ], 201);
    }

    /** This function is used to complete a todo-item
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function complete(Request $request, $id): JsonResponse
    {
        $todo = Todo::findOrFail($id);
        if ($todo->user_id !== $request->user->id) {
            return response()->json([
                'error' => 'Unauthorised'
            ], 403);
        }
        $todo->completed = true;
        $todo->save();

        return response()->json([
            'message' => 'Todo completed successfully'
        ], 200);
    }

    /** This function is used to in-complete a todo-item
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function inComplete(Request $request, $id): JsonResponse
    {
        $todo = Todo::findOrFail($id);
        if ($todo->user_id !== $request->user->id) {
            return response()->json([
                'error' => 'Unauthorised'
            ], 403);
        }
        $todo->completed = false;
        $todo->save();

        return response()->json([
            'message' => 'Todo in-completed successfully'
        ], 200);
    }

    /** This function is used to delete a todo-item
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function delete(Request $request, $id): JsonResponse
    {
        $todo = Todo::findOrFail($id);
        if ($todo->user_id !== $request->user->id) {
            return response()->json([
                'error' => 'Unauthorised'
            ], 403);
        }
        $todo->delete();

        return response()->json([
            'message' => 'Todo deleted successfully'
        ], 200);
    }

    /**
     * @param $user_id
     * @return JsonResponse
     */
    public function showUserTodos($user_id): JsonResponse
    {
        $todos = User::findOrFail($user_id)->todos;
        return response()->json($todos, 200);
    }
}
