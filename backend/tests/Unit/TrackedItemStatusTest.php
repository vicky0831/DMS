<?php

namespace Tests\Unit;

use App\Models\TrackedItem;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TrackedItemStatusTest extends TestCase
{
    #[Test]
    public function it_marks_items_without_expiry_as_active(): void
    {
        $item = new TrackedItem([
            'status' => TrackedItem::STATUS_ACTIVE,
            'expiry_date' => null,
        ]);

        self::assertSame(TrackedItem::STATUS_ACTIVE, $item->derived_status);
    }

    #[Test]
    public function it_marks_past_due_items_as_expired(): void
    {
        Carbon::setTestNow('2026-08-15 09:00:00');

        $item = new TrackedItem([
            'status' => TrackedItem::STATUS_ACTIVE,
            'expiry_date' => '2026-08-10',
        ]);

        self::assertSame(TrackedItem::STATUS_EXPIRED, $item->derived_status);

        Carbon::setTestNow();
    }

    #[Test]
    public function it_marks_near_due_items_as_expiring_soon(): void
    {
        Carbon::setTestNow('2026-08-15 09:00:00');

        $item = new TrackedItem([
            'status' => TrackedItem::STATUS_ACTIVE,
            'expiry_date' => '2026-09-01',
        ]);

        self::assertSame(TrackedItem::STATUS_EXPIRING_SOON, $item->derived_status);

        Carbon::setTestNow();
    }
}
