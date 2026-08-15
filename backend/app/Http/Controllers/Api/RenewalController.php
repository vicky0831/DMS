<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrackedItem;
use App\Models\Renewal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RenewalController extends Controller
{
    public function start(Request $request, string $trackedItem): JsonResponse
    {
        $company = $request->user()->companies()->firstOrFail();
        $item = TrackedItem::where('company_id', $company->id)->findOrFail($trackedItem);
        
        abort_unless($request->user()->can('update', $item), 403);

        $validated = $request->validate([
            'target_completion_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        // Change item status
        $item->update(['status' => TrackedItem::STATUS_RENEWAL_IN_PROGRESS]);

        // Create renewal record
        $renewal = Renewal::create([
            'tracked_item_id' => $item->id,
            'company_id' => $company->id,
            'started_by' => $request->user()->id,
            'status' => 'in_progress',
            'target_completion_date' => $validated['target_completion_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Mock basic steps based on item category for differentiation
        $steps = [
            ['name' => 'Prepare Documents', 'order' => 1],
            ['name' => 'Submit Application', 'order' => 2],
            ['name' => 'Await Approval', 'order' => 3],
        ];

        foreach ($steps as $step) {
            $renewal->steps()->create($step);
        }

        return response()->json([
            'message' => 'Renewal started successfully.',
            'data' => $renewal->load('steps'),
        ], 201);
    }
}
