<?php

namespace App\Console\Commands;

use App\Models\Reminder;
use App\Models\TrackedItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateReminders extends Command
{
    protected $signature = 'reminders:generate';
    protected $description = 'Generate reminders based on tracked item expiry dates and schedules';

    public function handle()
    {
        $this->info('Starting reminder generation...');

        // Fetch active items with an expiry date and a reminder schedule
        $items = TrackedItem::whereNotNull('expiry_date')
            ->whereNotNull('reminder_schedule')
            ->whereIn('status', [TrackedItem::STATUS_ACTIVE, TrackedItem::STATUS_EXPIRING_SOON])
            ->get();

        $generated = 0;

        foreach ($items as $item) {
            $schedule = $item->reminder_schedule; // assuming array like: [30, 15, 7, 0] (days before)

            if (!is_array($schedule)) {
                $schedule = json_decode($schedule, true) ?? [];
            }

            foreach ($schedule as $daysBefore) {
                // Ensure $daysBefore is an integer
                $daysBefore = (int) $daysBefore;
                $dueAt = $item->expiry_date->copy()->subDays($daysBefore)->startOfDay();

                // If due date is in the past or today, we might need to create it
                // We shouldn't create reminders for dates long past unless it's overdue notification
                if ($dueAt->isBefore(now()->subDays(2))) {
                    continue; // Skip if it's too old
                }

                // Check if a reminder already exists for this exact schedule (idempotency)
                $exists = Reminder::where('tracked_item_id', $item->id)
                    ->where('scheduled_days_before', $daysBefore)
                    ->exists();

                if (!$exists) {
                    // Decide channels, normally would read user preference or company setting
                    // For now, default to both email and whatsapp since it's flexible
                    $channels = ['email', 'whatsapp'];

                    foreach ($channels as $channel) {
                        Reminder::create([
                            'company_id' => $item->company_id,
                            'tracked_item_id' => $item->id,
                            'due_at' => $dueAt,
                            'channel' => $channel,
                            'status' => 'pending',
                            'scheduled_days_before' => $daysBefore,
                        ]);
                        $generated++;
                    }
                }
            }
        }

        $this->info("Generated {$generated} new reminders.");
    }
}
