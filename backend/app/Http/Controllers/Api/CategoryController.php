<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        abort_unless($request->user()->can('viewAny', Category::class), 403);
        $company = $request->user()->companies()->firstOrFail();

        return response()->json([
            'data' => Category::query()
                ->where('company_id', $company->id)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(['id', 'name', 'group_name', 'is_default', 'sort_order']),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $company = $request->user()->companies()->firstOrFail();
        abort_unless($request->user()->can('create', [Category::class, $company->id]), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'group_name' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:32'],
        ]);

        $category = Category::create([
            'company_id' => $company->id,
            'name' => $validated['name'],
            'group_name' => $validated['group_name'] ?? null,
            'color' => $validated['color'] ?? null,
            'is_default' => false,
            'sort_order' => Category::query()->where('company_id', $company->id)->max('sort_order') + 1,
        ]);

        return response()->json([
            'message' => 'Category created successfully.',
            'data' => $category,
        ], 201);
    }
}
