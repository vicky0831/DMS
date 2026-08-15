<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrackedItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TrackedItemController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        abort_unless($request->user()->can('viewAny', TrackedItem::class), 403);
        $company = $request->user()->companies()->firstOrFail();

        $query = TrackedItem::query()
            ->with(['category', 'versions'])
            ->where('company_id', $company->id)
            ->orderByDesc('updated_at');

        if ($search = trim((string) $request->string('search'))) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('reference_number', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($categoryId = $request->integer('category_id')) {
            $query->where('category_id', $categoryId);
        }

        $items = $query->paginate(20)->withQueryString();

        return response()->json([
            'data' => $items,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $company = $request->user()->companies()->firstOrFail();
        abort_unless($request->user()->can('create', [TrackedItem::class, $company->id]), 403);

        $validated = $request->validate([
            'category_id' => ['nullable', Rule::exists('categories', 'id')->where('company_id', $company->id)],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'responsible_user_id' => ['nullable', Rule::exists('company_user', 'user_id')->where('company_id', $company->id)],
            'issue_date' => ['nullable', 'date'],
            'expiry_date' => ['nullable', 'date'],
            'priority' => ['nullable', Rule::in(['low', 'normal', 'high', 'urgent'])],
            'notes' => ['nullable', 'string'],
            'tags' => ['nullable', 'array'],
            'reminder_schedule' => ['nullable', 'array'],
        ]);

        $item = TrackedItem::create([
            ...$validated,
            'company_id' => $company->id,
            'owner_user_id' => $request->user()->id,
            'status' => TrackedItem::STATUS_ACTIVE,
        ]);

        return response()->json([
            'message' => 'Tracked item created successfully.',
            'data' => $item->fresh(['category', 'versions']),
        ], 201);
    }

    public function show(Request $request, TrackedItem $trackedItem): JsonResponse
    {
        $this->assertCompanyAccess($request, $trackedItem);

        return response()->json([
            'data' => $trackedItem->load(['category', 'versions', 'reminders']),
        ]);
    }

    public function update(Request $request, TrackedItem $trackedItem): JsonResponse
    {
        $this->assertCompanyAccess($request, $trackedItem, 'update');

        $company = $request->user()->companies()->firstOrFail();

        $validated = $request->validate([
            'category_id' => ['nullable', Rule::exists('categories', 'id')->where('company_id', $company->id)],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'reference_number' => ['sometimes', 'nullable', 'string', 'max:255'],
            'responsible_user_id' => ['sometimes', 'nullable', Rule::exists('company_user', 'user_id')->where('company_id', $company->id)],
            'issue_date' => ['sometimes', 'nullable', 'date'],
            'expiry_date' => ['sometimes', 'nullable', 'date'],
            'priority' => ['sometimes', 'nullable', Rule::in(['low', 'normal', 'high', 'urgent'])],
            'notes' => ['sometimes', 'nullable', 'string'],
            'tags' => ['sometimes', 'nullable', 'array'],
            'reminder_schedule' => ['sometimes', 'nullable', 'array'],
            'status' => ['sometimes', Rule::in([
                TrackedItem::STATUS_ACTIVE,
                TrackedItem::STATUS_EXPIRING_SOON,
                TrackedItem::STATUS_RENEWAL_REQUIRED,
                TrackedItem::STATUS_RENEWAL_IN_PROGRESS,
                TrackedItem::STATUS_PENDING_APPROVAL,
                TrackedItem::STATUS_RENEWED,
                TrackedItem::STATUS_EXPIRED,
                TrackedItem::STATUS_CANCELLED,
            ])],
        ]);

        $trackedItem->fill($validated)->save();

        return response()->json([
            'message' => 'Tracked item updated successfully.',
            'data' => $trackedItem->fresh(['category', 'versions', 'reminders']),
        ]);
    }

    public function destroy(Request $request, TrackedItem $trackedItem): JsonResponse
    {
        $this->assertCompanyAccess($request, $trackedItem, 'delete');

        $trackedItem->delete();

        return response()->json([
            'message' => 'Tracked item archived successfully.',
        ]);
    }

    private function assertCompanyAccess(Request $request, TrackedItem $trackedItem, string $ability = 'view'): void
    {
        $company = $request->user()->companies()->firstOrFail();

        abort_unless($trackedItem->company_id === $company->id, 403);
        abort_unless($request->user()->can($ability, $trackedItem), 403);
    }

}
