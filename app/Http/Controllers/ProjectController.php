<?php
namespace App\Http\Controllers;

use App\Http\Controllers\ApiResponse;
use App\Models\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProjectController extends Controller
{
    public function index()
    {
        try {
            if (! Auth::check()) {
                return ApiResponse::error("Unauthorized", 401);
            }

            $projects = Project::where('user_id', Auth::id())->get();
            return ApiResponse::success($projects, "Projects retrieved successfully");
        } catch (Exception $e) {
            return ApiResponse::error("Failed to retrieve projects", 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (! $user) {
                return ApiResponse::error("Unauthorized", 401);
            }

            $request->validate([
                'name'        => 'required|string|max:255',
                'description' => 'nullable|string',
            ]);

            $project = DB::transaction(function () use ($request, $user) {
                return Project::create([
                    'user_id'     => $user->id,
                    'name'        => $request->name,
                    'description' => $request->description,
                ]);
            });

            return ApiResponse::success($project, "Project created successfully", 201);
        } catch (ValidationException $e) {
            return ApiResponse::error($e->errors(), 422);
        } catch (TokenExpiredException $e) {
            return ApiResponse::error("Token expired", 401);
        } catch (TokenInvalidException $e) {
            return ApiResponse::error("Invalid token", 401);
        } catch (JWTException $e) {
            return ApiResponse::error("Token not provided", 401);
        } catch (Exception $e) {
            return ApiResponse::error("Failed to create project", 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if (! Auth::check()) {
                return ApiResponse::error("Unauthorized", 401);
            }

            $project = Project::where('id', $id)->where('user_id', Auth::id())->first();

            if (! $project) {
                return ApiResponse::error("Forbidden: You do not own this project", 403);
            }

            $request->validate([
                'name'        => 'sometimes|string|max:255',
                'description' => 'nullable|string',
            ]);

            DB::transaction(function () use ($request, $project) {
                $project->update($request->only(['name', 'description']));
            });

            return ApiResponse::success($project, "Project updated successfully");
        } catch (ValidationException $e) {
            return ApiResponse::error($e->errors(), 422);
        } catch (Exception $e) {
            return ApiResponse::error("Failed to update project", 500);
        }
    }

    public function destroy($id)
    {
        try {
            if (! Auth::check()) {
                return ApiResponse::error("Unauthorized", 401);
            }

            $project = Project::where('id', $id)->where('user_id', Auth::id())->first();

            if (! $project) {
                return ApiResponse::error("Forbidden: You do not own this project", 403);
            }

            DB::transaction(function () use ($project) {
                $project->delete();
            });

            return ApiResponse::success(null, "Project deleted successfully");
        } catch (Exception $e) {
            return ApiResponse::error("Failed to delete project", 500);
        }
    }
}
