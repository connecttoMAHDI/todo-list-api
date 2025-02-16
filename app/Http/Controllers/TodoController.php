<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class TodoController extends Controller
{
    /**
     * Show paginated list of To-dos (related to authenticated user)
     *
     * GET /api/v1/todos
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $query = Auth::user()->todos();

        // Total to-dos
        $count = $query->count();

        // Query params with default values and validation
        $limit = max(1, (int) request()->query('limit', 10));
        $page = max(1, (int) request()->query('page', 1));
        $search = request()->query('search');
        $isCompleted = request()->query('is_completed');
        $sortBy = request()->query('sort_by', 'created_at');
        $sortOrder = request()->query('sort_order', 'asc');

        // Apply search filter if search term is provided
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        // Apply is_completed filter if provided
        if ($isCompleted !== null) {
            $isCompleted = filter_var($isCompleted, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($isCompleted !== null) {
                $query->where('is_completed', $isCompleted);
            }
        }

        // Apply sorting
        if (in_array($sortBy, ['id', 'title', 'created_at', 'updated_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Recalculate total count after all filters
        $count = $query->count();

        // Total pages
        $total_pages = ceil($count / $limit);

        // Calculate offset for pagination
        $offset = ($page - 1) * $limit;

        // Fetch to-dos with pagination
        $todos = $query->skip($offset)
            ->take($limit)
            ->get();

        // Find the 'from' and 'to' values
        $from = ($page - 1) * $limit + 1;
        $to = min($page * $limit, $count); // Ensure 'to' is not more than total count

        // If no todos set every meta data to zero
        if ($todos->isEmpty()) {
            $from = 0;
            $to = 0;
            $count = 0;
        }

        // Prepare and return the response
        return $this->successResponse(
            msg: 'To-dos retrieved successfully.',
            data: TodoResource::collection($todos),
            meta: [
                'from' => $from > $count ? 0 : $from,
                'to' => $to > $count ? 0 : $to,
                'total' => $count,
                'current_page' => (int) $page,
                'limit' => $limit,
                'last_page' => $total_pages,
            ]
        );
    }

    /**
     * Create a new Todo
     *
     * POST /api/v1/todos
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(TodoRequest $request)
    {
        // Create Todo
        $todo = Auth::user()->todos()
            ->create($request->validated());

        return $this->successResponse(
            'Todo created successfully.',
            new TodoResource($todo),
            Response::HTTP_CREATED
        );
    }

    /**
     * Show single Todo by id (related to authenticated user)
     *
     * GET /api/v1/todos/{id}
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Todo $todo)
    {
        // Ensure the Todo belongs to the user
        Gate::authorize('isOwner', $todo);

        return $this->successResponse(
            'Todo retrieved successfully.',
            new TodoResource($todo),
        );
    }

    /**
     * Update a Todo (related to authenticated user)
     *
     * PUT /api/v1/todos/{id}
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(TodoRequest $request, Todo $todo)
    {
        // Ensure the Todo belongs to the user
        Gate::authorize('isOwner', $todo);

        // Update Todo
        $todo->update($request->validated());

        return $this->successResponse(
            'Todo updated successfully.',
            new TodoResource($todo)
        );
    }

    /**
     * Delete a Todo (related to authenticated user)
     *
     * DELETE /api/v1/todos/{id}
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Todo $todo)
    {
        // Ensure the Todo belongs to the user
        Gate::authorize('isOwner', $todo);

        // Delete Todo
        $todo->delete();

        return $this->successResponse(
            statusCode: Response::HTTP_NO_CONTENT,
        );
    }
}
