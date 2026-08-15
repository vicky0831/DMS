import type { TrackedItemStatus } from '../types';

const STATUS_LABELS: Record<TrackedItemStatus, string> = {
    ACTIVE: 'Active',
    EXPIRING_SOON: 'Expiring soon',
    RENEWAL_REQUIRED: 'Renewal required',
    RENEWAL_IN_PROGRESS: 'Renewal in progress',
    PENDING_APPROVAL: 'Pending approval',
    RENEWED: 'Renewed',
    EXPIRED: 'Overdue',
    CANCELLED: 'Cancelled',
};

const STATUS_CHIP_CLASS: Record<TrackedItemStatus, string> = {
    ACTIVE: 'st-active',
    EXPIRING_SOON: 'st-soon',
    RENEWAL_REQUIRED: 'st-soon',
    RENEWAL_IN_PROGRESS: 'st-progress',
    PENDING_APPROVAL: 'st-pending',
    RENEWED: 'st-renewed',
    EXPIRED: 'st-overdue',
    CANCELLED: 'st-cancelled',
};

export function statusLabel(status: TrackedItemStatus): string {
    return STATUS_LABELS[status] ?? status;
}

export function statusChipClass(status: TrackedItemStatus): string {
    return STATUS_CHIP_CLASS[status] ?? 'st-progress';
}

export function formatDate(value: string | null): string {
    if (!value) {
        return 'No expiry set';
    }

    const date = new Date(value);

    if (Number.isNaN(date.getTime())) {
        return value;
    }

    return date.toLocaleDateString('en-MY', { day: '2-digit', month: 'short', year: 'numeric' });
}

export type UrgencyTone = 'alert' | 'amber' | 'jade';

export function daysUntil(value: string | null): number | null {
    if (!value) {
        return null;
    }

    const target = new Date(value);

    if (Number.isNaN(target.getTime())) {
        return null;
    }

    const today = new Date();
    today.setHours(0, 0, 0, 0);
    target.setHours(0, 0, 0, 0);

    return Math.round((target.getTime() - today.getTime()) / (1000 * 60 * 60 * 24));
}

export function urgencyLabel(value: string | null): { text: string; tone: UrgencyTone } {
    const days = daysUntil(value);

    if (days === null) {
        return { text: 'No expiry set', tone: 'jade' };
    }

    if (days < 0) {
        return { text: `Overdue ${Math.abs(days)}d`, tone: 'alert' };
    }

    if (days === 0) {
        return { text: 'Due today', tone: 'alert' };
    }

    if (days <= 14) {
        return { text: `Due in ${days}d`, tone: 'amber' };
    }

    return { text: `Due in ${days}d`, tone: 'jade' };
}
