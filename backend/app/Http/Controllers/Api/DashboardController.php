<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrackedItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $company = $request->user()->companies()->firstOrFail();
        $today = now()->startOfDay();
        $cutoff31 = $today->copy()->addDays(31);

        $items = TrackedItem::query()
            ->where('company_id', $company->id);

        $overdue = (clone $items)->whereDate('expiry_date', '<', $today)->count();
        $expiring7 = (clone $items)->whereBetween('expiry_date', [$today, now()->addDays(7)->endOfDay()])->count();
        $expiring30 = (clone $items)->whereBetween('expiry_date', [$today, now()->addDays(30)->endOfDay()])->count();
        $expiring60 = (clone $items)->whereBetween('expiry_date', [$today, now()->addDays(60)->endOfDay()])->count();
        $expiring90 = (clone $items)->whereBetween('expiry_date', [$today, now()->addDays(90)->endOfDay()])->count();

        return response()->json([
            'data' => [
                'overview' => [
                    'total' => (clone $items)->count(),
                    'active' => (clone $items)->where(function ($query) use ($cutoff31) {
                        $query->whereNull('expiry_date')->orWhereDate('expiry_date', '>=', $cutoff31);
                    })->count(),
                    'overdue' => $overdue,
                    'expiring_soon' => $expiring30,
                    'renewal_in_progress' => (clone $items)->where('status', TrackedItem::STATUS_RENEWAL_IN_PROGRESS)->count(),
                    'completed_this_month' => (clone $items)->where('status', TrackedItem::STATUS_RENEWED)->count(),
                ],
                'urgency' => [
                    'overdue' => $overdue,
                    'expiring_7_days' => $expiring7,
                    'expiring_30_days' => $expiring30,
                    'expiring_60_days' => $expiring60,
                    'expiring_90_days' => $expiring90,
                ],
                'recent_activity' => [],
            ],
        ]);
    }
}
